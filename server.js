import express from "express";
import fetch from "node-fetch";
import cors from "cors";

const app = express();
const PORT = 3000;

app.use(cors());

const TOKEN = "eyJhbGciOiJIUzUxMiJ9.eyJzdWIiOiJkZW5uaXMud29uZzAwMkB1bWIuZWR1IiwidXNlcklkIjoiNDk2NDFhYzAtMTY3NC0xMWYwLTg2M2ItZjczMGFkZGM2OGJhIiwic2NvcGVzIjpbIlRFTkFOVF9BRE1JTiJdLCJzZXNzaW9uSWQiOiJhYmM4NGY5Yy1mNGFjLTQ3YWYtODg0YS1iNWZiNmNhMjJkYjQiLCJleHAiOjE3NDU0MDU5ODQsImlzcyI6InRoaW5nc2JvYXJkLmNsb3VkIiwiaWF0IjoxNzQ1Mzc3MTg0LCJmaXJzdE5hbWUiOiJEZW5uaXMiLCJsYXN0TmFtZSI6IldvbmciLCJlbmFibGVkIjp0cnVlLCJpc1B1YmxpYyI6ZmFsc2UsImlzQmlsbGluZ1NlcnZpY2UiOmZhbHNlLCJwcml2YWN5UG9saWN5QWNjZXB0ZWQiOnRydWUsInRlcm1zT2ZVc2VBY2NlcHRlZCI6dHJ1ZSwidGVuYW50SWQiOiIxMDVhMjBiMC0xNjY3LTExZjAtODYzYi1mNzMwYWRkYzY4YmEiLCJjdXN0b21lcklkIjoiMTM4MTQwMDAtMWRkMi0xMWIyLTgwODAtODA4MDgwODA4MDgwIn0.gF7i_lZ_Njm9u9fgzPAymzzdXtriV94ZaEOmLRcFTLKA0udPkXjWS9rQcWw_e0yIrRP9h89wxsp3TSJcXbQpbQ"
const DEVICE_ID = "8b534c60-1667-11f0-8f83-43727cd6bc90";
const KEYS = "TempF,TempC,Humidity";

app.get("/api/weather", async (req, res) => {
    try {
        const response = await fetch(`https://thingsboard.cloud/api/plugins/telemetry/DEVICE/${DEVICE_ID}/values/timeseries?keys=${KEYS}`, {
            headers: {
                "X-Authorization": `Bearer ${TOKEN}`
            }
        });

        const data = await response.json();
        res.json(data);
    } catch (err) {
        console.error("Proxy error:", err);
        res.status(500).json({ error: "Proxy server error" });
    }
});

app.listen(PORT, () => {
    console.log(`✅ Proxy server running at http://localhost:${PORT}`);
});
