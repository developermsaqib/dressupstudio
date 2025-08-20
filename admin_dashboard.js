document.addEventListener("DOMContentLoaded", () => {
  // --- Elements ---
  const sidebarItems = document.querySelectorAll(".sidebar ul li");
  const contentSections = document.querySelectorAll(".content-section");

  const addProductBtn = document.getElementById("addProductBtn");
  const productModal = document.getElementById("productModal");
  const productForm = document.getElementById("productForm");
  const productTableBody = document.getElementById("productTableBody");

  const addCategoryBtn = document.getElementById("addCategoryBtn");
  const categoryModal = document.getElementById("categoryModal");
  const categoryForm = document.getElementById("categoryForm");
  const categoryTableBody = document.getElementById("categoryTableBody");

  const addUserBtn = document.getElementById("addUserBtn");
  const userModal = document.getElementById("userModal");
  const userForm = document.getElementById("userForm");
  const userTableBody = document.getElementById("userTableBody");

  const addSubcategoryBtn = document.getElementById("addSubcategoryBtn");
  const subcategoryModal = document.getElementById("subcategoryModal");
  const subcategoryForm = document.getElementById("subcategoryForm");
  const subcategoryTableBody = document.getElementById("subcategoryTableBody");

  const orderTableBody = document.getElementById("orderTableBody");

  const totalProductsSpan = document.getElementById("total-products");

  let products = [];
  let categories = [];
  let users = [];
  let subcategories = [];
  let orders = [];

  // --- Sidebar Navigation ---
  sidebarItems.forEach((item) => {
    item.addEventListener("click", () => {
      sidebarItems.forEach((i) => i.classList.remove("active"));
      item.classList.add("active");
      const target = item.getAttribute("data-section");
      contentSections.forEach((sec) => sec.classList.remove("active"));
      document.getElementById(target).classList.add("active");

      if (target === "dashboard") fetchProducts();
      if (target === "categories") fetchCategories();
      if (target === "users") fetchUsers();
      if (target === "orders") fetchOrders();
      if (target === "subcategories") fetchSubcategories();
    });
  });

  // --- Fetch & Render Products ---
  async function fetchProducts() {
    try {
      const res = await fetch("get_products.php");
      products = await res.json();
      renderProducts();
      updateDashboard();
    } catch (err) {
      console.error("Fetch products error:", err);
    }
  }

  function renderProducts() {
    if (!productTableBody) return;
    productTableBody.innerHTML = "";
    products.forEach((p) => {
      const row = productTableBody.insertRow();
      row.dataset.id = p.id;
      row.innerHTML = `
                <td>${p.id}</td>
                <td><img src="${p.image}" alt="${p.name}" width="50"></td>
                <td>${p.name}</td>
                <td>$${parseFloat(p.price).toFixed(2)}</td>
                <td>${p.stock}</td>
                <td>${p.category_name || ""}</td>
                <td>
                    <button class="btn edit-btn" data-id="${p.id}">Edit</button>
                    <button class="btn delete-btn" data-id="${
                      p.id
                    }">Delete</button>
                </td>
            `;
    });
  }

  function updateDashboard() {
    if (totalProductsSpan) totalProductsSpan.textContent = products.length;
  }

  // --- Fetch & Render Categories ---
  async function fetchCategories() {
    try {
      const res = await fetch("get_categories.php");
      categories = await res.json();
      console.log("Cat:", categories);
      renderCategories();
      populateCategoryDropdown();
    } catch (err) {
      console.error("Fetch categories error:", err);
    }
  }

  function renderCategories() {
    if (!categoryTableBody) return;
    categoryTableBody.innerHTML = "";
    categories.forEach((cat) => {
      const row = categoryTableBody.insertRow();
      row.dataset.id = cat.id;
      row.innerHTML = `
      <td>${cat.id}</td>
<td>${cat.name}</td>
<td>
  <img src="${cat.image ? cat.image : "https://via.placeholder.com/50"}" 
       alt="${cat.name}" width="50" 
       style="vertical-align:middle; margin-right:8px;">
</td>
<td>
  <button class="btn edit-category-btn" data-id="${cat.id}">Edit</button>
  <button class="btn delete-category-btn" data-id="${cat.id}">Delete</button>
</td>
    `;
    });
  }
  // --- Edit/Delete Category ---
  categoryTableBody?.addEventListener("click", async (e) => {
    const btn = e.target.closest("button");
    if (!btn) return;
    const id = btn.dataset.id;
    const category = categories.find((cat) => String(cat.id) === String(id));
    if (btn.classList.contains("edit-category-btn")) {
      document.getElementById("categoryId").value = category ? category.id : "";
      document.getElementById("categoryName").value = category
        ? category.name
        : "";
      // Optionally set image preview if you want
      categoryModal.style.display = "flex";
    } else if (btn.classList.contains("delete-category-btn")) {
      if (confirm("Are you sure?")) {
        try {
          const res = await fetch("delete_category.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ id }),
          });
          const data = await res.json();
          if (data.success) fetchCategories();
          else alert("Delete failed");
        } catch (err) {
          console.error("Delete category error:", err);
        }
      }
    }
  });

  // --- Populate Category Dropdown in Product Form ---
  function populateCategoryDropdown(selectedId = "") {
    let select = document.getElementById("productCategory");
    if (!select) {
      const div = document.createElement("div");
      div.classList.add("form-group");
      div.innerHTML = `
                <label for="productCategory">Category:</label>
                <select id="productCategory" required></select>
            `;
      productForm.insertBefore(div, productForm.querySelector("button"));
      select = document.getElementById("productCategory");
    }
    select.innerHTML = '<option value="">Select Category</option>';
    categories.forEach((c) => {
      const opt = document.createElement("option");
      opt.value = c.id;
      opt.textContent = c.name;
      if (selectedId == c.id) opt.selected = true;
      select.appendChild(opt);
    });
  }

  // --- Product Modal ---
  addProductBtn?.addEventListener("click", async () => {
    productForm.reset();
    document.getElementById("productId").value = "";
    await fetchCategories();
    productModal.querySelector("h2").textContent = "Add New Product";
    productModal.style.display = "flex";
  });

  // --- Category Modal ---
  addCategoryBtn?.addEventListener("click", () => {
    categoryForm.reset();
    document.getElementById("categoryId").value = "";
    categoryModal.style.display = "flex";
  });

  // --- User Modal ---
  addUserBtn?.addEventListener("click", () => {
    userForm.reset();
    document.getElementById("userId").value = "";
    if (userModal) userModal.style.display = "flex";
  });

  // --- Subcategory Modal ---
  addSubcategoryBtn?.addEventListener("click", async () => {
    subcategoryForm.reset();
    document.getElementById("subcategoryId").value = "";
    await fetchCategories();
    subcategoryModal.style.display = "flex";
    // Populate category dropdown in subcategory form
    let select = document.getElementById("subcategoryCategory");
    select.innerHTML = '<option value="">Select Category</option>';
    categories.forEach((c) => {
      const opt = document.createElement("option");
      opt.value = c.id;
      opt.textContent = c.name;
      select.appendChild(opt);
    });
  });

  // --- Close Modals ---
  document.querySelectorAll(".close-button").forEach((btn) => {
    btn.addEventListener(
      "click",
      () => (btn.closest(".modal").style.display = "none")
    );
  });
  window.addEventListener("click", (e) => {
    if (e.target.classList.contains("modal")) e.target.style.display = "none";
  });

  // --- Edit/Delete Product ---
  productTableBody?.addEventListener("click", async (e) => {
    const btn = e.target.closest("button");
    if (!btn) return;
    const id = btn.dataset.id;
    const product = products.find((p) => p.id == id);
    if (btn.classList.contains("edit-btn")) {
      document.getElementById("productId").value = product.id;
      document.getElementById("productName").value = product.name;
      document.getElementById("productPrice").value = product.price;
      document.getElementById("productStock").value = product.stock;
      // Clear file input and show image preview
      const fileInput = document.getElementById("productImage");
      if (fileInput) fileInput.value = "";
      // Add or update image preview
      let preview = document.getElementById("productImagePreview");
      if (!preview) {
        preview = document.createElement("img");
        preview.id = "productImagePreview";
        preview.style.maxWidth = "100px";
        preview.style.display = "block";
        fileInput.parentNode.appendChild(preview);
      }
      preview.src = product.image;
      await fetchCategories();
      populateCategoryDropdown(product.category_id);
      await fetchSubcategories();
      populateSubcategoryDropdown(product.subcategory_id);
      productModal.querySelector("h2").textContent = "Edit Product";
      productModal.style.display = "flex";
    } else if (btn.classList.contains("delete-btn")) {
      if (confirm("Are you sure?")) {
        try {
          const res = await fetch("delete_product.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ id }),
          });
          const data = await res.json();
          if (data.success) fetchProducts();
          else alert("Delete failed");
        } catch (err) {
          console.error("Delete product error:", err);
        }
      }
    }
  });

  // --- Fetch & Render Users ---
  async function fetchUsers() {
    try {
      const res = await fetch("get_users.php");
      users = await res.json();
      renderUsers();
    } catch (err) {
      console.error("Fetch users error:", err);
    }
  }

  function renderUsers() {
    if (!userTableBody) return;
    userTableBody.innerHTML = "";
    users.forEach((u) => {
      const row = userTableBody.insertRow();
      row.dataset.id = u.id;
      row.innerHTML = `
        <td>${u.id}</td>
        <td>${u.firstname} ${u.lastname}</td>
        <td>${u.email}</td>
        <td>${u.role}</td>
        <td>
          <button class="btn edit-user-btn" data-id="${u.id}">Edit</button>
          <button class="btn delete-user-btn" data-id="${u.id}">Delete</button>
        </td>
      `;
    });
  }

  // --- User Form Submit ---
  userForm?.addEventListener("submit", async (e) => {
    e.preventDefault();
    const id = document.getElementById("userId").value;
    const payload = {
      firstname: document.getElementById("userFirstname").value,
      lastname: document.getElementById("userLastname").value,
      email: document.getElementById("userEmail").value,
      role: document.getElementById("userRole").value,
    };
    const url = id ? "update_user.php" : "add_user.php";
    if (id) payload.id = id;
    try {
      const res = await fetch(url, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(payload),
      });
      const data = await res.json();
      if (data.success) {
        if (userModal) userModal.style.display = "none";
        fetchUsers();
      } else alert("Error saving user");
    } catch (err) {
      console.error("User save error:", err);
    }
  });

  // --- Edit/Delete User ---
  userTableBody?.addEventListener("click", async (e) => {
    const btn = e.target.closest("button");
    if (!btn) return;
    const id = btn.dataset.id;
    const user = users.find((u) => u.id == id);
    if (btn.classList.contains("edit-user-btn")) {
      document.getElementById("userId").value = user.id;
      document.getElementById("userFirstname").value = user.firstname;
      document.getElementById("userLastname").value = user.lastname;
      document.getElementById("userEmail").value = user.email;
      document.getElementById("userRole").value = user.role;
      if (userModal) userModal.style.display = "flex";
    } else if (btn.classList.contains("delete-user-btn")) {
      if (confirm("Are you sure?")) {
        try {
          const res = await fetch("delete_user.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ id }),
          });
          const data = await res.json();
          if (data.success) fetchUsers();
          else alert("Delete failed");
        } catch (err) {
          console.error("Delete user error:", err);
        }
      }
    }
  });

  // --- Fetch & Render Orders ---
  async function fetchOrders() {
    try {
      const res = await fetch("get_orders.php");
      orders = await res.json();
      renderOrders();
    } catch (err) {
      console.error("Fetch orders error:", err);
    }
  }

  function renderOrders() {
    if (!orderTableBody) return;
    orderTableBody.innerHTML = "";

    orders.forEach((o) => {
      const row = orderTableBody.insertRow();
      row.dataset.id = o.id;

      let total = 0;

      // ensure cart_data is an array
      let cartData = [];
      if (typeof o.cart_data === "string") {
        try {
          cartData = JSON.parse(o.cart_data);
        } catch (e) {
          console.error("Invalid cart_data JSON:", o.cart_data);
        }
      } else if (Array.isArray(o.cart_data)) {
        cartData = o.cart_data;
      }

      if (Array.isArray(cartData)) {
        total = cartData.reduce((sum, item) => {
          const price = parseFloat(item.price.replace(/[^0-9.]/g, ""));
          console.log(
            "item:",
            item.name,
            "price:",
            price,
            "qty:",
            item.quantity
          );
          return sum + price * (item.quantity || 1);
        }, 0);
      }

      row.innerHTML = `
        <td>${o.id}</td>
        <td>${o.user_name || ""}</td>
        <td>Rs. ${total.toFixed(2)}</td>
        <td>${o.status}</td>
        <td>
          <button class="btn update-order-btn" data-id="${o.id}">Update</button>
        </td>
      `;
    });
  }

  // --- Update Order Status ---
  orderTableBody?.addEventListener("click", async (e) => {
    const btn = e.target.closest("button");
    if (!btn) return;
    const id = btn.dataset.id;
    if (btn.classList.contains("update-order-btn")) {
      const newStatus = prompt("Enter new status:");
      if (newStatus) {
        try {
          const res = await fetch("update_order.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ id, status: newStatus }),
          });
          const data = await res.json();
          if (data.success) fetchOrders();
          else alert("Update failed");
        } catch (err) {
          console.error("Update order error:", err);
        }
      }
    }
  });

  // --- Fetch & Render Subcategories ---
  async function fetchSubcategories() {
    try {
      const res = await fetch("get_subcategories.php");
      subcategories = await res.json();
      renderSubcategories();
      populateSubcategoryDropdown();
    } catch (err) {
      console.error("Fetch subcategories error:", err);
    }
  }

  function renderSubcategories() {
    if (!subcategoryTableBody) return;
    subcategoryTableBody.innerHTML = "";
    subcategories.forEach((s) => {
      const row = subcategoryTableBody.insertRow();
      row.dataset.id = s.id;
      row.innerHTML = `
        <td>${s.id}</td>
        <td>${s.category_name || ""}</td>
        <td>${s.name}</td>
        <td>
          <button class="btn edit-subcategory-btn" data-id="${
            s.id
          }">Edit</button>
          <button class="btn delete-subcategory-btn" data-id="${
            s.id
          }">Delete</button>
        </td>
      `;
    });
  }

  // --- Populate Subcategory Dropdown in Product Form ---
  function populateSubcategoryDropdown(selectedId = "") {
    let select = document.getElementById("productSubcategory");
    if (!select) return;
    select.innerHTML = '<option value="">Select Subcategory</option>';
    subcategories.forEach((s) => {
      const opt = document.createElement("option");
      opt.value = s.id;
      opt.textContent = s.name;
      if (selectedId == s.id) opt.selected = true;
      select.appendChild(opt);
    });
  }

  // --- Subcategory Form Submit ---
  subcategoryForm?.addEventListener("submit", async (e) => {
    e.preventDefault();
    const id = document.getElementById("subcategoryId").value;
    const payload = {
      name: document.getElementById("subcategoryName").value,
      category_id: document.getElementById("subcategoryCategory").value,
    };
    const url = id ? "update_subcategory.php" : "add_subcategory.php";
    if (id) payload.id = id;
    try {
      const res = await fetch(url, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(payload),
      });
      const data = await res.json();
      if (data.success) {
        subcategoryModal.style.display = "none";
        fetchSubcategories();
      } else alert("Error saving subcategory");
    } catch (err) {
      console.error("Subcategory save error:", err);
    }
  });

  // --- Edit/Delete Subcategory ---
  subcategoryTableBody?.addEventListener("click", async (e) => {
    const btn = e.target.closest("button");
    if (!btn) return;
    const id = btn.dataset.id;
    const subcat = subcategories.find((s) => s.id == id);
    if (btn.classList.contains("edit-subcategory-btn")) {
      document.getElementById("subcategoryId").value = subcat.id;
      document.getElementById("subcategoryName").value = subcat.name;
      // Populate category dropdown in subcategory form
      let select = document.getElementById("subcategoryCategory");
      select.innerHTML = '<option value="">Select Category</option>';
      categories.forEach((c) => {
        const opt = document.createElement("option");
        opt.value = c.id;
        opt.textContent = c.name;
        if (subcat.category_id == c.id) opt.selected = true;
        select.appendChild(opt);
      });
      subcategoryModal.style.display = "flex";
    } else if (btn.classList.contains("delete-subcategory-btn")) {
      if (confirm("Are you sure?")) {
        try {
          const res = await fetch("delete_subcategory.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ id }),
          });
          const data = await res.json();
          if (data.success) fetchSubcategories();
          else alert("Delete failed");
        } catch (err) {
          console.error("Delete subcategory error:", err);
        }
      }
    }
  });

  // --- Update Product Subcategory Dropdown on Category Change ---
  document
    .getElementById("productCategory")
    ?.addEventListener("change", function () {
      const selectedCategoryId = this.value;
      let select = document.getElementById("productSubcategory");
      select.innerHTML = '<option value="">Select Subcategory</option>';
      subcategories
        .filter((s) => s.category_id == selectedCategoryId)
        .forEach((s) => {
          const opt = document.createElement("option");
          opt.value = s.id;
          opt.textContent = s.name;
          select.appendChild(opt);
        });
    });

  // --- Initial Load ---
  fetchCategories();
  fetchProducts();
  fetchUsers();
  fetchOrders();
  fetchSubcategories();

  // --- Product Form Submit (Add via PHP POST, Edit via AJAX) ---
  productForm?.addEventListener("submit", async (e) => {
    const productId = document.getElementById("productId").value;
    if (productId) {
      // Edit mode: AJAX to update_product.php
      e.preventDefault();
      const formData = new FormData(productForm);
      formData.append("id", productId);
      try {
        const res = await fetch("update_product.php", {
          method: "POST",
          body: formData,
        });
        const data = await res.json();
        if (data.success) {
          productModal.style.display = "none";
          fetchProducts();
        } else {
          alert(data.error || "Error updating product");
        }
      } catch (err) {
        console.error("Product update error:", err);
        alert("Product update failed. Check console for details.");
      }
    }
    // else: add mode, allow normal PHP POST
  });
});
