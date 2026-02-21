// Peweka - Main JavaScript

// Toggle mobile menu
function toggleMenu() {
    document.getElementById('navMenu').classList.toggle('active');
}

// Navbar scroll effect
window.addEventListener('scroll', function () {
    var navbar = document.getElementById('navbar');
    if (navbar) {
        if (window.scrollY > 50) {
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.remove('scrolled');
        }
    }
});

// Smooth scroll
document.querySelectorAll('a[href^="#"]').forEach(function (anchor) {
    anchor.addEventListener('click', function (e) {
        var target = document.querySelector(this.getAttribute('href'));
        if (target) {
            e.preventDefault();
            target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            // Close mobile menu
            document.getElementById('navMenu').classList.remove('active');
        }
    });
});

// Intersection Observer for animations
document.addEventListener('DOMContentLoaded', function () {
    var observer = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-in');
            }
        });
    }, { threshold: 0.1 });

    document.querySelectorAll('.product-card, .feature-card, .section-header').forEach(function (el) {
        observer.observe(el);
    });

    // Initialize Cart Badge
    updateCartBadge();
});

// Cart Functions
function getCart() {
    var cart = localStorage.getItem('peweka_cart');
    return cart ? JSON.parse(cart) : [];
}

function saveCart(cart) {
    localStorage.setItem('peweka_cart', JSON.stringify(cart));
    updateCartBadge();
}

function addToCart(item) {
    var cart = getCart();
    var existingItem = cart.find(i => i.variant_id === item.variant_id);

    if (existingItem) {
        existingItem.quantity += parseInt(item.quantity);
    } else {
        cart.push(item);
    }

    saveCart(cart);
}

function updateCartBadge() {
    var cart = getCart();
    var badge = document.getElementById('cartBadge');
    if (badge) {
        var total = cart.reduce((sum, item) => sum + parseInt(item.quantity), 0);
        if (total > 0) {
            badge.textContent = total;
            badge.style.display = 'block';
        } else {
            badge.style.display = 'none';
        }
    }
}
