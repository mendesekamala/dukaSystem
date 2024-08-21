function liveSearch() {
    const searchQuery = document.getElementById('search').value;
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'search_product.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            const products = JSON.parse(xhr.responseText);
            if (products.length > 0) {
                const product = products[0];
                document.getElementById('product_name').value = product.product_name;
                document.getElementById('product_nickname').value = product.product_nickname;
                document.getElementById('product_description').value = product.product_description;
                document.getElementById('buying_price').value = product.buying_price;
                document.getElementById('selling_price').value = product.selling_price;
                document.getElementById('quantity').value = product.quantity;
                document.getElementById('expiry_date').value = product.expiry_date;
                document.getElementById('date_bought').value = product.date_bought;
                document.getElementById('supplier').value = product.supplier;
            }
        }
    };
    xhr.send('search_query=' + encodeURIComponent(searchQuery));
}




function purchaseProduct() {
    const productName = document.getElementById('product_name').value;
    const productNickname = document.getElementById('product_nickname').value;
    const productDescription = document.getElementById('product_description').value;
    const buyingPrice = document.getElementById('buying_price').value;
    const sellingPrice = document.getElementById('selling_price').value;
    const quantity = document.getElementById('quantity').value;
    const expiryDate = document.getElementById('expiry_date').value;
    const dateBought = document.getElementById('date_bought').value;
    const supplier = document.getElementById('supplier').value;

    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'purchase_product.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            alert(xhr.responseText);
        }
    };
    const data = `product_name=${encodeURIComponent(productName)}&product_nickname=${encodeURIComponent(productNickname)}&product_description=${encodeURIComponent(productDescription)}&buying_price=${encodeURIComponent(buyingPrice)}&selling_price=${encodeURIComponent(sellingPrice)}&quantity=${encodeURIComponent(quantity)}&expiry_date=${encodeURIComponent(expiryDate)}&date_bought=${encodeURIComponent(dateBought)}&supplier=${encodeURIComponent(supplier)}`;
    xhr.send(data);
}





function showForm(formType) {
    const purchaseTab = document.getElementById('purchase-tab');
    const createTab = document.getElementById('create-tab');
    const purchaseForm = document.getElementById('purchase-form');
    const createForm = document.getElementById('create-form');

    if (formType === 'purchase') {
        purchaseTab.classList.add('active-tab');
        createTab.classList.remove('active-tab');
        purchaseForm.classList.add('active-form');
        createForm.classList.remove('active-form');
    } else {
        createTab.classList.add('active-tab');
        purchaseTab.classList.remove('active-tab');
        createForm.classList.add('active-form');
        purchaseForm.classList.remove('active-form');
    }
}

// Default to showing the purchase form
showForm('purchase');