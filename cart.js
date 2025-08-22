document.addEventListener("DOMContentLoaded", function () {
  const cartItemsContainer = document.getElementById("cart-items");
  const subtotalEl = document.getElementById("subtotal");
  const taxEl = document.getElementById("tax");
  const totalEl = document.getElementById("total");
  const cartCount = document.getElementById("cart-count");

  let cart = JSON.parse(localStorage.getItem("cart")) || [];

  // --- GUEST CART/LOGIN LOGIC ---
  // Helper: Check if user is logged in (set by PHP)
  function isLoggedIn() {
    return window.isUserLoggedIn === true;
  }

  // Save cart to localStorage for guests only
  function saveCart() {
    if (!isLoggedIn()) {
      localStorage.setItem("cart", JSON.stringify(cart));
    }
  }

  // On add-to-cart for guests, update localStorage
  // (Assume add-to-cart button calls addToCart(item) for guests)
  window.addToCart = function (item) {
    if (!isLoggedIn()) {
      // If item exists, increment quantity
      let found = false;
      cart = cart.map((c) => {
        if (c.id === item.id) {
          c.quantity += item.quantity;
          found = true;
        }
        return c;
      });
      if (!found) cart.push(item);
      saveCart();
      renderCart();
    } else {
      // For logged-in users, fallback to server-side add (existing logic)
      window.location.href = `add_to_cart.php?id=${item.id}&qty=${item.quantity}`;
    }
  };

  function calculateTotals() {
    let subtotal = 0;
    cart.forEach((item) => {
      const price = parseFloat(item.price.replace(/[^\d.]/g, "")) || 0;
      subtotal += price * item.quantity;
    });
    const tax = subtotal * 0.05;
    const total = subtotal + tax;

    subtotalEl.textContent = `Rs ${subtotal.toFixed(2)}`;
    taxEl.textContent = `Rs ${tax.toFixed(2)}`;
    totalEl.textContent = `Rs ${total.toFixed(2)}`;
    cartCount.textContent = `${cart.length} items`;
  }

  function removeItem(id) {
    cart = cart.filter((item) => item.id !== id);
    saveCart();
    renderCart();
  }

  function updateQuantity(id, type) {
    cart = cart.map((item) => {
      if (item.id === id) {
        const newQty = type === "inc" ? item.quantity + 1 : item.quantity - 1;
        item.quantity = newQty < 1 ? 1 : newQty;
      }
      return item;
    });
    saveCart();
    renderCart();
  }

  function renderCart() {
    cartItemsContainer.innerHTML = "";
    if (cart.length === 0) {
      cartItemsContainer.innerHTML = `<p>Your cart is empty.</p>`;
      subtotalEl.textContent = taxEl.textContent = totalEl.textContent = "Rs 0";
      cartCount.textContent = "0 items";
      return;
    }

    cart.forEach((item) => {
      const itemDiv = document.createElement("div");
      itemDiv.className = "cart-item";
      itemDiv.innerHTML = `
        <div class="item-image">
          <img src="${item.image}" alt="${item.name}">
        </div>
        <div class="item-details">
          <h3>${item.name}</h3>
        </div>
        <div class="item-price">${item.price}</div>
        <div class="quantity-control">
          <button class="quantity-btn" onclick="updateQuantity(${item.id}, 'dec')">-</button>
          <input class="quantity" value="${item.quantity}" disabled>
          <button class="quantity-btn" onclick="updateQuantity(${item.id}, 'inc')">+</button>
        </div>
        <div class="remove-item" onclick="removeItem(${item.id})">Remove</div>
      `;
      cartItemsContainer.appendChild(itemDiv);
    });

    calculateTotals();
  }

  window.removeItem = removeItem;
  window.updateQuantity = updateQuantity;

  renderCart();
});

// Checkout Modal
function openCheckout() {
  const modal = document.getElementById("checkoutModal");
  modal.style.display = "flex";
  document.body.style.overflow = "hidden";

  const checkoutBody = modal.querySelector(".checkout-body");

  const paymentSection = document.createElement("div");
  paymentSection.className = "checkout-section";
  paymentSection.innerHTML = `
    <h4 class="section-title">Payment Information</h4>
    <div class="form-group">
      <label class="form-label">Cardholder Name</label>
      <input type="text" class="form-control" placeholder="John Doe">
    </div>
    <div class="form-group">
      <label class="form-label">Card Number</label>
      <input type="text" class="form-control" placeholder="1234 5678 9012 3456">
    </div>
    <div style="display: flex; gap: 10px;">
      <div class="form-group" style="flex:1">
        <label class="form-label">Expiry Date</label>
        <input type="text" class="form-control" placeholder="MM/YY">
      </div>
      <div class="form-group" style="flex:1">
        <label class="form-label">CVV</label>
        <input type="text" class="form-control" placeholder="123">
      </div>
    </div>
    <div class="checkout-actions">
      <button class="btn btn-primary" onclick="placeOrder()">Place Order</button>
    </div>
  `;

  if (!checkoutBody.innerHTML.includes("Payment Information")) {
    checkoutBody.appendChild(paymentSection);
  }
}

function placeOrder() {
  if (!isLoggedIn()) {
    // Redirect guests to login page
    window.location.href = "login.php?redirect=cart.php";
    return;
  }
  // ...existing code for placing order (for logged-in users)...
  alert("✅ Order Placed Successfully!");
  localStorage.removeItem("cart");
  window.location.href = "index.php";
}

// --- Expose guest cart for login merge ---
window.getGuestCart = function () {
  return localStorage.getItem("cart") || "[]";
};
