<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Product_model extends CI_Model
{
    private function _apply_global_discount($product)
    {
        if (!$product)
            return $product;

        $discount_percent = (int) get_setting('global_discount_percent', 0);
        $discount_name = get_setting('global_discount_name', '');

        $product->original_price = $product->price;
        $product->discount_percent = 0;
        $product->discount_name = '';

        if ($discount_percent > 0) {
            $discount_amount = ($product->price * $discount_percent) / 100;
            // Round to nearest hundred or thousand if needed, but simple subtraction for now
            $product->price = $product->original_price - $discount_amount;
            $product->discount_percent = $discount_percent;
            $product->discount_name = $discount_name;
        }

        return $product;
    }

    public function get_all($active_only = TRUE, $category_slug = NULL, $limit = NULL, $offset = 0, $apply_discount = TRUE)
    {
        $this->db->select('products.*, categories.name as category_name, categories.slug as category_slug');
        $this->db->from('products');
        $this->db->join('categories', 'categories.id = products.category_id', 'left');

        if ($active_only) {
            $this->db->where('products.is_active', 1);
        }

        if ($category_slug) {
            $this->db->where('categories.slug', $category_slug);
        }

        $this->db->order_by('products.created_at', 'DESC');

        if ($limit) {
            $this->db->limit($limit, $offset);
        }

        $results = $this->db->get()->result();
        foreach ($results as &$r) {
            if ($apply_discount) {
                $r = $this->_apply_global_discount($r);
            }
        }
        return $results;
    }

    public function count_all($active_only = TRUE)
    {
        if ($active_only) {
            $this->db->where('is_active', 1);
        }
        return $this->db->count_all_results('products');
    }

    public function get_by_id($id, $apply_discount = TRUE)
    {
        $this->db->select('products.*, categories.name as category_name, categories.id as category_id');
        $this->db->from('products');
        $this->db->join('categories', 'categories.id = products.category_id', 'left');
        $this->db->where('products.id', $id);
        $product = $this->db->get()->row();

        if ($apply_discount) {
            return $this->_apply_global_discount($product);
        }

        return $product;
    }

    public function get_by_slug($slug)
    {
        $this->db->select('products.*, categories.name as category_name, categories.id as category_id');
        $this->db->from('products');
        $this->db->join('categories', 'categories.id = products.category_id', 'left');
        $this->db->where('products.slug', $slug);
        $product = $this->db->get()->row();
        return $this->_apply_global_discount($product);
    }

    public function get_variants($product_id, $apply_discount = TRUE)
    {
        $results = $this->db->get_where('product_variants', ['product_id' => $product_id])->result();
        if ($apply_discount) {
            foreach ($results as &$r) {
                $r->original_price = $r->price;
                $discount_percent = (int) get_setting('global_discount_percent', 0);
                if ($discount_percent > 0) {
                    $r->price = $r->original_price - ($r->original_price * $discount_percent / 100);
                }
            }
        }
        return $results;
    }

    public function get_variant_by_id($id, $apply_discount = TRUE)
    {
        $variant = $this->db->get_where('product_variants', ['id' => $id])->row();
        if ($variant && $apply_discount) {
            $variant->original_price = $variant->price;
            $discount_percent = (int) get_setting('global_discount_percent', 0);
            if ($discount_percent > 0) {
                $variant->price = $variant->original_price - ($variant->original_price * $discount_percent / 100);
            }
        }
        return $variant;
    }

    public function get_available_sizes($product_id)
    {
        $this->db->select('DISTINCT(size) as size');
        $this->db->where('product_id', $product_id);
        $this->db->where('stock >', 0);
        return $this->db->get('product_variants')->result();
    }

    public function get_available_colors($product_id, $size = NULL)
    {
        $this->db->where('product_id', $product_id);
        $this->db->where('stock >', 0);
        if ($size) {
            $this->db->where('size', $size);
        }
        return $this->db->get('product_variants')->result();
    }

    public function create($data)
    {
        $this->db->insert('products', $data);
        return $this->db->insert_id();
    }

    public function update($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('products', $data);
    }

    public function delete($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete('products');
    }

    public function add_variant($data)
    {
        $this->db->insert('product_variants', $data);
        return $this->db->insert_id();
    }

    public function delete_variants($product_id)
    {
        $this->db->where('product_id', $product_id);
        return $this->db->delete('product_variants');
    }

    public function sync_min_price($product_id)
    {
        // Get the minimum price from active variants with stock
        $this->db->select_min('price');
        $this->db->where('product_id', $product_id);
        $this->db->where('stock >', 0);
        $result = $this->db->get('product_variants')->row();

        if ($result && $result->price !== NULL) {
            $this->db->where('id', $product_id);
            return $this->db->update('products', ['price' => $result->price]);
        }
        return FALSE;
    }

    // ===================================
    // SHOP & FILTERING
    // ===================================

    private function _apply_filters($filters)
    {
        // 1. Active products only
        $this->db->where('products.is_active', 1);

        // 2. Category Filter
        if (!empty($filters['category'])) {
            $this->db->where('categories.slug', $filters['category']);
        }

        // 3. Price Filter
        if (!empty($filters['min_price'])) {
            $this->db->where('products.price >=', $filters['min_price']);
        }
        if (!empty($filters['max_price'])) {
            $this->db->where('products.price <=', $filters['max_price']);
        }

        // 4. Color Filter (using subquery or exists)
        if (!empty($filters['color'])) {
            $this->db->where("EXISTS (SELECT 1 FROM product_variants pv WHERE pv.product_id = products.id AND pv.color = " . $this->db->escape($filters['color']) . ")");
        }

        // 5. Search Keyword
        if (!empty($filters['search'])) {
            $this->db->group_start();
            $this->db->like('products.name', $filters['search']);
            $this->db->or_like('products.description', $filters['search']);
            $this->db->group_end();
        }
    }

    public function get_shop_products($limit, $offset, $filters = [])
    {
        $this->db->select('products.*, categories.name as category_name, categories.slug as category_slug');
        $this->db->from('products');
        $this->db->join('categories', 'categories.id = products.category_id', 'left');

        $this->_apply_filters($filters);

        // Sorting
        $sort = isset($filters['sort']) ? $filters['sort'] : 'newest';
        switch ($sort) {
            case 'price_low':
                $this->db->order_by('products.price', 'ASC');
                break;
            case 'price_high':
                $this->db->order_by('products.price', 'DESC');
                break;
            case 'newest':
            default:
                $this->db->order_by('products.created_at', 'DESC');
                break;
        }

        $this->db->limit($limit, $offset);
        $results = $this->db->get()->result();
        foreach ($results as &$r) {
            $r = $this->_apply_global_discount($r);
        }
        return $results;
    }

    public function count_shop_products($filters = [])
    {
        $this->db->from('products');
        $this->db->join('categories', 'categories.id = products.category_id', 'left');
        $this->_apply_filters($filters);
        return $this->db->count_all_results();
    }

    public function get_all_colors()
    {
        $this->db->distinct();
        $this->db->select('color');
        $this->db->from('product_variants');
        $this->db->order_by('color', 'ASC');
        return $this->db->get()->result();
    }

    public function get_price_range()
    {
        $this->db->select_min('price', 'min_price');
        $this->db->select_max('price', 'max_price');
        $this->db->where('is_active', 1);
        return $this->db->get('products')->row();
    }

    // ===================================
    // MULTI IMAGE SUPPORT
    // ===================================
    public function get_images($product_id)
    {
        return $this->db->get_where('product_images', ['product_id' => $product_id])->result();
    }

    public function get_image_by_id($id)
    {
        return $this->db->get_where('product_images', ['id' => $id])->row();
    }

    public function add_image($data)
    {
        $this->db->insert('product_images', $data);
        return $this->db->insert_id();
    }

    public function get_low_stock_variants($threshold = 5)
    {
        $this->db->select('product_variants.*, products.name as product_name, products.image');
        $this->db->from('product_variants');
        $this->db->join('products', 'products.id = product_variants.product_id');
        $this->db->where('product_variants.stock <', $threshold);
        $this->db->where('products.is_active', 1);
        $this->db->order_by('product_variants.stock', 'ASC');
        return $this->db->get()->result();
    }

    public function delete_image($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete('product_images');
    }
}
