let cart = JSON.parse(localStorage.getItem("cart")) || [];

function saveCart() {
    localStorage.setItem("cart", JSON.stringify(cart));
}

function updateUI() {
    let itemCount = 0;
    let total = 0;

    cart.forEach(item => {
        itemCount += item.quantity;
        total += item.price * item.quantity;
    });

    let itemEl = document.getElementById("itemCount");
    let totalEl = document.getElementById("totalPrice");

    if (itemEl) itemEl.innerText = itemCount;
    if (totalEl) totalEl.innerText = total;
}

function updateFinalTotal() {
    let total = 0;

    cart.forEach(item => {
        total += item.price * item.quantity;
    });

    let final = document.getElementById("finalTotal");
    if (final) final.innerText = total;
}

function addToCart(name, price, qtyInput) {
    let qty = parseInt(qtyInput.value);

    let existing = cart.find(item => item.name === name);

    if (existing) {
        existing.quantity += qty;
    } else {
        cart.push({
            name: name,
            price: price,
            quantity: qty
        });
    }

    saveCart();
    updateUI();
    updateFinalTotal();
}

document.addEventListener("DOMContentLoaded", () => {
    loadCart();
    updateUI();
    updateFinalTotal();
});

function removeItem(index) {
    cart.splice(index, 1);
    saveCart();
    location.reload();
}

function loadCart() {
    let container = document.getElementById("cartContainer");
    if (!container) return;

    container.innerHTML = "";

    cart.forEach((item, index) => {
        let div = document.createElement("div");

        div.innerHTML = `
            <p>${item.name} - ${item.price} DA x ${item.quantity}</p>
            <button onclick="removeItem(${index})">Remove</button>
        `;

        container.appendChild(div);
    });
}

function checkout() {
    window.location.href = "checkout.html";
}

function placeOrder() {
    fetch("../PHP/save_order.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({ products: cart })
    })
    .then(res => res.text())
    .then(data => {
        if (data === "success") {
            localStorage.removeItem("cart");
            cart = [];
            updateUI();
        }
    });
}