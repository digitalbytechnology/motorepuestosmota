import Chart from "chart.js/auto";

window.renderDashboardCharts = function (payload) {
    const { ventasLabels, ventasData, topLabels, topData } = payload;

    const ventasEl = document.getElementById("ventasChart");
    if (ventasEl) {
        new Chart(ventasEl, {
            type: "line",
            data: {
                labels: ventasLabels,
                datasets: [
                    {
                        label: "Ventas (Q)",
                        data: ventasData,
                        tension: 0.3,
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
            },
        });
    }

    const topEl = document.getElementById("topProductosChart");
    if (topEl) {
        new Chart(topEl, {
            type: "bar",
            data: {
                labels: topLabels,
                datasets: [
                    {
                        label: "Unidades",
                        data: topData,
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
            },
        });
    }
};
