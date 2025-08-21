document.addEventListener("DOMContentLoaded", function () {
  const products = [
    {
      id: 1,
      name: "Silver Grey Elegant Dress",
      price: 2499,
      image: "dress.webp",
      rating: 4,
      badge: "Sale",
    },
    {
      id: 2,
      name: "Baby Feeder",
      price: 890,
      image: "fider.webp",
      rating: 5,
      badge: "Hot",
    },
    {
      id: 3,
      name: "Bluetooth Headphones",
      price: 999,
      image: "bluthoth.webp",
      rating: 3,
      badge: "",
    },
    {
      id: 4,
      name: "Skincare Set",
      price: 49.99,
      oldPrice: 59.99,
      image: "skincare.webp",
      rating: 3,
      badge: "",
    },
    {
      id: 5,
      name: "Perfume",
      price: 89.99,
      oldPrice: 1000,
      image: "men3900 perfume.webp",
      rating: 4,
      badge: "Sale",
    },
    {
      id: 6,
      name: "Perfume Set",
      price: 150.0,
      oldPrice: 2000,
      image: "perfumes.jpg",
      rating: 5,
      badge: "Sale",
    },
    // Add your more products here
  ];

  const productGrid = document.querySelector(".product-grid");
  // const cartCount = document.querySelector('.cart-count'); // Removed duplicate declaration
  let cart = JSON.parse(localStorage.getItem("cart")) || [];

  const cartCount = document.querySelector(".cart-count");

  // function updateCartCount() {
  //   const cart = JSON.parse(localStorage.getItem("cart")) || [];
  //   const total = cart.reduce((sum, item) => sum + item.quantity, 0);
  //   if (cartCount) cartCount.textContent = total;
  // }

  function saveCart() {
    localStorage.setItem("cart", JSON.stringify(cart));
    // updateCartCount();
  }

  let allProducts = [];

  // Fetch products from backend (dynamic)
  fetch("get_products.php")
    .then((response) => response.json())
    .then((dynamicProducts) => {
      allProducts = [...products, ...dynamicProducts];
      displayProducts(allProducts);
      localStorage.setItem("allProducts", JSON.stringify(allProducts));
    })
    .catch(() => {
      allProducts = products;
      displayProducts(products);
      localStorage.setItem("allProducts", JSON.stringify(products));
    });

  function displayProducts(productList) {
    productGrid.innerHTML = "";
    productList.forEach((product) => {
      const card = document.createElement("div");
      card.className = "product-card";
      let ratingStars = "";
      for (let i = 1; i <= 5; i++) {
        ratingStars +=
          i <= (product.rating || 0)
            ? '<i class="fas fa-star"></i>'
            : '<i class="far fa-star"></i>';
      }

      card.innerHTML = `
        ${
          product.badge
            ? `<span class="product-badge">${product.badge}</span>`
            : ""
        }
        <div class="product-image"><img src="${product.image}" alt="${
        product.name
      }"></div>
        <div class="product-info">
          <h3>${product.name}</h3>
          <div class="product-price"><span class="current-price">Rs ${
            product.price
          }</span></div>
          <div class="product-rating">${ratingStars}</div>
          <button class="view-details" data-id="${
            product.id
          }">View Details</button>
          <button class="add-to-cart" data-id="${
            product.id
          }">Add to Cart</button>
        </div>
      `;
      // Use your existing CSS classes for styling
      productGrid.appendChild(card);
    });
  }

  productGrid.addEventListener("click", function (e) {
    if (e.target.classList.contains("add-to-cart")) {
      const id = parseInt(e.target.getAttribute("data-id"));
      // Redirect to product page for quantity selection and server-side add to cart
      window.location.href = `product.php?id=${id}`;
    }
    if (e.target.classList.contains("view-details")) {
      const id = parseInt(e.target.getAttribute("data-id"));
      window.location.href = `product.php?id=${id}`;
    }
  });

  const productSearch = document.getElementById("productSearch");
  if (productSearch) {
    productSearch.addEventListener("input", function () {
      const query = this.value.toLowerCase();
      const filtered = allProducts.filter((product) =>
        product.name.toLowerCase().includes(query)
      );
      displayProducts(filtered);
    });
  }

  updateCartCount();
});
