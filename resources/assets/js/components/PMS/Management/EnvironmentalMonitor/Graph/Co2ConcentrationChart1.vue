<script>
import { Line } from 'vue-chartjs'
import chartjsPluginAnnotation from 'chartjs-plugin-annotation'

export default {
    extends: Line,

    props: {
        height: Number,
        width: Number,
        device_id: Number,
    },

    data() {
        return {
            co2_data: [],
            options: {
                responsive: true,
                maintainAspectRatio: false,
                elements: {
                    point: {
                        radius: 1.5,
                    },
                },
                scales: {
                    yAxes: [
                        {
                            ticks: {
                                min: 0,
                                max: 3000,
                                stepSize: 500,
                                reverse: false,
                                // = SPRINT_04 TASK127
                                fontSize: 15,
                                // = SPRINT_04 TASK127
                                padding: 10,
                            },
                            gridLines: {
                                display: false,
                            },
                            scaleLabel: {
                                display: true,
                                labelString: 'PPM',
                            },
                        },
                    ],
                    xAxes: [
                        {
                            type: 'time',
                            time: {
                                unit: 'minute',
                                unitStepSize: 60,
                                displayFormats: {
                                    minute: 'hh:mm',
                                },
                            },
                            gridLines: {
                                display: false,
                            },
                            ticks: {
                                beginAtZero: true,
                                fontSize: 10,
                            },
                        },
                    ],
                },
                annotation: {
                    annotations: [
                        {
                            type: 'line',
                            mode: 'horizontal',
                            scaleID: 'y-axis-0',
                            value: 2500,
                            borderColor: 'red',
                            borderWidth: 1,
                        },
                        {
                            type: 'line',
                            mode: 'horizontal',
                            scaleID: 'y-axis-0',
                            value: 1000,
                            borderColor: 'rgb(255,192,0)',
                            borderWidth: 1,
                        },
                        {
                            type: 'line',
                            mode: 'horizontal',
                            scaleID: 'y-axis-0',
                            value: 800,
                            borderColor: 'blue',
                            borderWidth: 1,
                        },
                        {
                            type: 'line',
                            mode: 'horizontal',
                            scaleID: 'y-axis-0',
                            value: 500,
                            borderColor: 'green',
                            borderWidth: 1,
                        },
                    ],
                },
            },
        }
    },

    created() {
        this.getCo2ProcessDataToday()
    },

    methods: {
        /**
         * @name getCo2ProcessDataToday
         * @desc Get the process data of the netvox device
         *
         * @returns null
         */
        getCo2ProcessDataToday() {
            axios
                .get('getNetvoxProcessedDataHotel/' + this.device_id)
                .then(response => {
                    var today = new Date()
                    var dd = String(today.getDate()).padStart(2, '0')
                    var mm = String(today.getMonth() + 1).padStart(2, '0')
                    var yyyy = today.getFullYear()
                    var hh = String(today.getHours()).padStart(2, '0')
                    var mmm = String(today.getMinutes()).padStart(2, '0')
                    var ss = String(today.getSeconds()).padStart(2, '0')
                    var time = hh + ':' + mmm + ':' + ss
                    var today_date = yyyy + '-' + mm + '-' + dd

                    var yesterday = new Date(today)
                    yesterday.setDate(yesterday.getDate() - 1)
                    var dd_y = String(yesterday.getDate()).padStart(2, '0')
                    var mm_y = String(yesterday.getMonth() + 1).padStart(2, '0')
                    var yyyy_y = yesterday.getFullYear()
                    var yesterday_time = hh + ':' + mmm + ':' + ss
                    var yesterday_date = yyyy_y + '-' + mm_y + '-' + dd_y
                    var yesterday_date_time = yesterday_date + ' ' + yesterday_time

                    this.co2_data.push({ x: yesterday_date + ' ' + hh + ':00:00' })

                    for (let i in response.data) {
                        if (
                            response.data[i]['CREATED_AT'].split(' ')[0] == today_date ||
                            response.data[i]['CREATED_AT'].split(' ')[0] == yesterday_date
                        ) {
                            if (response.data[i]['CREATED_AT'] >= yesterday_date_time) {
                                if (response.data[i]['DATA']['co2']) {
                                    this.co2_data.push({
                                        x: response.data[i]['CREATED_AT'],
                                        y: response.data[i]['DATA']['co2'],
                                    })
                                }
                            }
                        }
                    }
                })
                .then(response => this.initializeChart())
                .catch(errors => {
                    console.log(errors)
                })
        },

        /**
         * @name initializeChart
         * @desc Create a chart using Chart JS
         *
         * @returns null
         */
        initializeChart() {
            this.addPlugin(chartjsPluginAnnotation)
            this.renderChart(
                {
                    labels: [],
                    datasets: [
                        {
                            label: '本日のCO2 濃度',
                            data: this.getCo2Data,
                            backgroundColor: 'rgb(75, 192, 192)',
                            borderColor: 'rgb(75, 192, 192)',
                            pointBorderColor: 'rgb(75, 192, 192)',
                            fill: false,
                        },
                    ],
                },
                this.options
            )
        },
    },

    computed: {
        getCo2Data() {
            return this.co2_data
        },
    },

    mounted() {
        /**
         * @desc Listen to NewDeviceDataEvent for new process data
         *
         * @returns null
         */
        Echo.channel('newdevice-data').listen('NewDeviceDataEvent', value => {
            this.getCo2ProcessDataToday()
        })
    },
    beforeDestroy() {
        Echo.leave('newdevice-data')
    },
}
</script>
