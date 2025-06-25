// Currency conversion rates (example rates - replace with actual rates or fetch from an API)
const exchangeRates = {
    'LKR': 1,      // Base currency
    'USD': 0.0033  // 1 LKR = 0.0033 USD (example rate)
};

// Format price based on selected currency
function formatPrice(amount, currency) {
    const convertedAmount = amount * exchangeRates[currency];
    
    const formatter = new Intl.NumberFormat(undefined, {
        style: 'currency',
        currency: currency,
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });
    
    // For LKR, we'll use a custom formatter since Intl.NumberFormat might not format it correctly
    if (currency === 'LKR') {
        return `LKR ${convertedAmount.toFixed(2)}`;
    }
    
    return formatter.format(convertedAmount);
}

// Update all prices on the page
function updatePrices(currency) {
    // Update price elements
    document.querySelectorAll('[data-price]').forEach(element => {
        const originalPrice = parseFloat(element.getAttribute('data-price'));
        if (!isNaN(originalPrice)) {
            element.textContent = formatPrice(originalPrice, currency);
        }
    });
    
    // Update price inputs
    document.querySelectorAll('[data-price-input]').forEach(input => {
        const originalPrice = parseFloat(input.getAttribute('data-price-input'));
        if (!isNaN(originalPrice)) {
            const convertedPrice = (originalPrice * exchangeRates[currency]).toFixed(2);
            input.value = convertedPrice;
        }
    });
}

// Listen for currency change events
document.addEventListener('alpine:init', () => {
    window.addEventListener('currency-changed', (event) => {
        const { currency } = event.detail;
        // Store the selected currency in localStorage for persistence
        localStorage.setItem('selectedCurrency', currency);
        // Update all prices on the page
        updatePrices(currency);
    });
    
    // Initialize with saved currency or default to LKR
    const savedCurrency = localStorage.getItem('selectedCurrency') || 'LKR';
    if (savedCurrency) {
        // Find and set the currency dropdown to the saved value
        const dropdown = document.querySelector('[x-data*="selectedCurrency"]');
        if (dropdown) {
            Alpine.data('selectedCurrency', savedCurrency);
            updatePrices(savedCurrency);
        }
    }
});
