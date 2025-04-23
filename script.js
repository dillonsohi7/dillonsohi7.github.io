function toggleDarkMode() {
    document.body.classList.toggle("dark");
}

function toggleMobileNav() {
    document.getElementById("nav-links").classList.toggle("show");
}

async function refreshData() {
    const spinner = document.getElementById("spinner");
    spinner.classList.remove("hidden");

    try {
        const response = await fetch("http://localhost:3000/api/weather");
        const data = await response.json();

        const tempF = parseFloat(data.TempF[0].value).toFixed(1);
        const tempC = parseFloat(data.TempC[0].value).toFixed(1);
        const humidity = parseFloat(data.Humidity[0].value).toFixed(0);
        const timestamp = new Date(parseInt(data.TempF[0].ts)).toLocaleTimeString();

        document.getElementById("tempC").textContent = tempC;
        document.getElementById("tempF").textContent = tempF;
        document.getElementById("humidity").textContent = humidity;
        document.getElementById("timestamp").textContent = timestamp;
    } catch (err) {
        console.error("Failed to fetch weather:", err);
        alert("❌ Could not fetch weather data.");
    } finally {
        spinner.classList.add("hidden");
    }
}

// Auto-refresh every 5 minutes
setInterval(refreshData, 300000); // 300000ms = 5min
window.onload = refreshData;
