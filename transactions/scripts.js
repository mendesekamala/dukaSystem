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