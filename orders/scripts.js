function liveSearch() {
    const searchQuery = document.getElementById('search').value;
    if (searchQuery.length === 0) {
        document.getElementById('suggestions').innerHTML = '';
        return;
    }

    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'search_product.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            const products = JSON.parse(xhr.responseText);
            let suggestionsHTML = '';
            products.forEach(product => {
                suggestionsHTML += `<div class="suggestion-item" onclick="selectProduct(${product.id})">${product.product_name}</div>`;
            });
            document.getElementById('suggestions').innerHTML = suggestionsHTML;
        }
    };
    xhr.send('search_query=' + encodeURIComponent(searchQuery));
}

function selectProduct(productId) {
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'get_product.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            const product = JSON.parse(xhr.responseText);
            document.getElementById('product_name').value = product.product_name;
            document.getElementById('product_nickname').value = product.product_nickname;
            document.getElementById('expiry_date').value = product.expiry_date;
            document.getElementById('buying_price').value = product.buying_price;
            document.getElementById('selling_price').value = product.selling_price;
            document.getElementById('quantity').value = '';
            document.getElementById('suggestions').innerHTML = '';
            document.getElementById('search').value = product.product_name;
        }
    };
    xhr.send('product_id=' + productId);
}

function validateSellingPrice() {
    const sellingPrice = parseFloat(document.getElementById('selling_price').value);
    const buyingPrice = parseFloat(document.getElementById('buying_price').value);

    if (sellingPrice < buyingPrice) {
        alert("Selling price cannot be less than buying price");
        return false;
    }

    return true;
}

let orderItems = [];
let grandTotal = 0;

function addProduct() {
    const productName = document.getElementById('product_name').value;
    const quantity = document.getElementById('quantity').value;
    const sellingPrice = document.getElementById('selling_price').value;

    if (!productName || !quantity || !sellingPrice) {
        alert("Please complete all fields");
        return;
    }

    if (!validateSellingPrice()) {
        return;
    }

    const total = quantity * sellingPrice;
    grandTotal += total;
    orderItems.push({ productName, quantity, sellingPrice, total });

    updateOrderList();
}

function updateOrderList() {
    const orderList = document.getElementById('orderItems');
    orderList.innerHTML = '';

    orderItems.forEach((item, index) => {
        const li = document.createElement('li');
        li.textContent = `${index + 1}. ${item.productName}, ${item.quantity} @ ${item.sellingPrice} = ${item.total}`;
        orderList.appendChild(li);
    });

    document.getElementById('grandTotal').textContent = grandTotal;
}

function toggleDebtField() {
    const paymentMethod = document.getElementById('payment_method').value;
    const debtInput = document.getElementById('debt_input');

    if (paymentMethod === 'debt') {
        debtInput.style.display = 'block';
    } else {
        debtInput.style.display = 'none';
        debtInput.value = '';
    }
}

function completeOrder() {
    const customerName = document.getElementById('customer_name').value;
    const paymentMethod = document.getElementById('payment_method').value;
    const debtAmount = paymentMethod === 'debt' ? document.getElementById('debt_input').value : 'NIL';

    if (!customerName || orderItems.length === 0) {
        alert("Please complete the order form");
        return;
    }

    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'complete_order.php', true);
    xhr.setRequestHeader('Content-Type', 'application/json');
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            const response = JSON.parse(xhr.responseText);
            alert(`Order completed successfully! Order Number: ${response.order_number}`);
            window.location.reload();
        }
    };
    xhr.send(JSON.stringify({ customerName, orderItems, paymentMethod, debtAmount }));
}
