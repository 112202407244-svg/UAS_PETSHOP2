(function () {
    const baseUrl = window.APP_BASE_URL || "/";
 
    const formatRupiah = (value) => {
    const number = Number(value) || 0;
    return new Intl.NumberFormat("id-ID").format(number);
    };
 
    const parseJson = async (response) => {
    const data = await response.json();
    if (!response.ok) {
    throw new Error(data.message || "Permintaan gagal diproses.");
    }
    return data;
    };
 
    const extractDestinationResults = (payload) => {
    if (Array.isArray(payload?.data)) {
    return payload.data;
    }
    if (Array.isArray(payload?.results)) {
    return payload.results;
    }
    if (Array.isArray(payload?.rajaongkir?.results)) {
    return payload.rajaongkir.results;
    }
    return [];
    };
 
    const extractShippingResults = (payload) => {
    if (Array.isArray(payload?.data)) {
    return payload.data;
    }
    if (Array.isArray(payload?.rajaongkir?.results?.[0]?.costs)) {
    return payload.rajaongkir.results[0].costs;
    }
    return [];
    };
 
    const initCheckout = () => {
    const form = document.querySelector("[data-checkout-form]");
    if (!form) {
    return;
}
 
const destinationInput = document.querySelector("[data-destination-search]");
    const destinationResults = document.getElementById("destination_results");
    const destinationId = document.getElementById("destination_id");
    const provinceInput = document.getElementById("province");
    const cityInput = document.getElementById("city");
    const courierSelect = document.getElementById("courier");
    const shippingPanel = document.getElementById("shipping_panel");
    const courierServiceInput = document.getElementById("courier_service");
    const shippingCostInput = document.getElementById("shipping_cost");
    const shippingCostText = document.getElementById("shipping_cost_text");
    const grandTotalText = document.getElementById("grand_total_text");
    const checkButton = document.querySelector("[data-check-ongkir]");
    const totalWeight = Number(checkButton?.dataset.weight || 0);
    const subtotal = Number(grandTotalText?.dataset.subtotal || 0);
 
    let destinationTimer;
 
const updateGrandTotal = (shippingCost) => {
shippingCostText.textContent = `Rp ${formatRupiah(shippingCost)}`;
grandTotalText.textContent = `Rp ${formatRupiah(subtotal + shippingCost)}`;
};
 
const resetShippingOptions = (message) => {
courierServiceInput.value = "";
shippingCostInput.value = "0";
updateGrandTotal(0);
shippingPanel.innerHTML = `<div class="muted-text">${message}</div>`;
};
 
const renderDestinations = (items) => {
if (!items.length) {
destinationResults.style.display = "block";
destinationResults.innerHTML = '<div class="search-result-item">Lokasi tidak ditemukan.</div>';
return;
}
 
destinationResults.style.display = "block";
destinationResults.innerHTML = items
.map((item) => {
const id = item.id || item.destination_id || item.city_id || "";
const label = item.label || item.subdistrict_name || item.city_name || item.name || "Tujuan";
const province = item.province_name || item.province || "";
const city = item.city_name || item.city || label;
 
return `
<button
type="button"
class="search-result-item"
data-destination-id="${String(id)}"
data-province="${String(province)}"
data-city="${String(city)}"
data-label="${String(label)}">
<strong>${label}</strong><br>
<span>${province}</span>
</button>
`;
})
.join("");
};
 
const renderShippingOptions = (items, courier) => {
if (!items.length) {
resetShippingOptions("Layanan ongkir tidak tersedia untuk tujuan ini.");
return;
}
 
shippingPanel.innerHTML = items
.map((item, index) => {
const code = item.code || courier.toUpperCase();
const service = item.service || item.description || item.name || `Service ${index + 1}`;
const cost = Number(item.cost || item.price || item.value || 0);
const etd = item.etd || item.estimate || item.duration || "Estimasi belum tersedia";
 
return `
<button
type="button"
class="shipping-option"
data-courier="${courier}"
data-service="${String(service)}"
data-cost="${cost}">
<strong>${code.toUpperCase()} - ${service}</strong>
<span>Ongkir: Rp ${formatRupiah(cost)}</span>
<span>Estimasi: ${etd}</span>
</button>
`;
})
.join("");
};
 
destinationInput?.addEventListener("input", () => {
clearTimeout(destinationTimer);
const keyword = destinationInput.value.trim();
 
if (keyword.length < 3) {
destinationResults.style.display = "none";
return;
}
 
destinationTimer = window.setTimeout(async () => {
try {
const response = await fetch(`${baseUrl}index.php?url=ongkir/cari`, {
method: "POST",
headers: {
"Content-Type": "application/x-www-form-urlencoded",
},
body: new URLSearchParams({ keyword }),
});
 
const payload = await parseJson(response);
renderDestinations(extractDestinationResults(payload));
} catch (error) {
destinationResults.style.display = "block";
destinationResults.innerHTML = `<div class="search-result-item">${error.message}</div>`;
}
}, 350);
});
 
destinationResults?.addEventListener("click", (event) => {
const button = event.target.closest("[data-destination-id]");
if (!button) {
return;
}
 
destinationId.value = button.dataset.destinationId || "";
provinceInput.value = button.dataset.province || "";
cityInput.value = button.dataset.city || "";
destinationInput.value = button.dataset.label || "";
destinationResults.style.display = "none";
resetShippingOptions("Tujuan dipilih. Klik cek ongkir untuk menampilkan layanan.");
});
 
checkButton?.addEventListener("click", async () => {
const destination = destinationId.value.trim();
const courier = courierSelect.value.trim();
 
if (!destination) {
window.alert("Pilih kota atau kecamatan tujuan terlebih dahulu.");
return;
}
 
if (!courier) {
window.alert("Pilih kurir terlebih dahulu.");
return;
}
 
shippingPanel.innerHTML = '<div class="muted-text">Mengambil data ongkir...</div>';
 
try {
const response = await fetch(`${baseUrl}index.php?url=ongkir/cek`, {
method: "POST",
headers: {
"Content-Type": "application/x-www-form-urlencoded",
},
body: new URLSearchParams({
destination,
weight: String(totalWeight),
courier,
}),
});
 
const payload = await parseJson(response);
renderShippingOptions(extractShippingResults(payload), courier);
} catch (error) {
resetShippingOptions(error.message);
}
});
 
shippingPanel?.addEventListener("click", (event) => {
const option = event.target.closest(".shipping-option");
if (!option) {
return;
}
 
shippingPanel.querySelectorAll(".shipping-option").forEach((element) => {
element.classList.remove("is-selected");
});
 
option.classList.add("is-selected");
 
const shippingCost = Number(option.dataset.cost || 0);
courierServiceInput.value = option.dataset.service || "";
shippingCostInput.value = String(shippingCost);
updateGrandTotal(shippingCost);
});
 
courierSelect?.addEventListener("change", () => {
resetShippingOptions("Kurir diganti. Klik cek ongkir untuk memuat ulang layanan.");
});
};
 
document.addEventListener("DOMContentLoaded", initCheckout);
})();