const ticksStyle = {
    fontColor: "#495057",
    fontStyle: "bold"
};
export const invoice2Barchart = {
    type: "bar",
    data: {
        labels: ["Jan-2020", "Feb-2020", "Mar-2020"],
        datasets: [
            {
                // one line graph
                label: "Amount",
                data: [0, 0, 0],
                backgroundColor: [
                    "rgba(60,141,188,0.9)", // Blue
                    "rgba(30,141,188,0.9)",
                    "rgba(20,141,188,0.9)"
                ],
                borderColor: [
                    "rgb(180, 180, 180)",
                    "rgb(180, 180, 180)",
                    "rgb(180, 180, 180)"
                ],
                borderWidth: 1
            }
        ]
    },
    aux: {
        amount: 0,
        perctg: 0.0
    },
    options: {
        maintainAspectRatio: false,
        tooltips: {
            mode: "index",
            intersect: true,
            callbacks: {
                label: function(tooltipItem, data) {
                    var datasetLabel =
                        data.datasets[tooltipItem.datasetIndex].label ||
                        "Other";
                    return (
                        datasetLabel +
                        ": " +
                        tooltipItem.value.replace(
                            /(\d)(?=(\d{3})+(?!\d))/g,
                            "$1,"
                        )
                    );
                }
            }
        },
        hover: {
            mode: "index",
            intersect: true
        },
        legend: {
            display: false
        },
        scales: {
            yAxes: [
                {
                    // display: false,
                    gridLines: {
                        display: true,
                        lineWidth: "4px",
                        color: "rgba(0, 0, 0, .2)",
                        zeroLineColor: "transparent"
                    },
                    ticks: $.extend(
                        {
                            beginAtZero: true,

                            // Include a dollar sign in the ticks
                            callback: function(value, index, values) {
                                if (value >= 1000000) {
                                    value /= 1000000;
                                    value += "m";
                                } else if (value >= 1000) {
                                    value /= 1000;
                                    value += "k";
                                }
                                return "$ " + value;
                            }
                        },
                        ticksStyle
                    )
                }
            ],
            xAxes: [
                {
                    display: true,
                    gridLines: {
                        display: false
                    },
                    ticks: ticksStyle
                }
            ]
        }
    }
};

export default invoice2Barchart;
