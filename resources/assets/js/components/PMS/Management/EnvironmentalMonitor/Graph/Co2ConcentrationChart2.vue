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
            co2_data_today: [],
            co2_data_yesterday: [],
            starts_at: '',
            ends_at: '',
            options: {
                tooltips: {
                    mode: 'index',
                    callbacks: {
                        title: function () {
                            return null
                        },
                        label: function (item, data) {
                            var today = new Date()
                            var dd_t = String(today.getDate()).padStart(2, '0')
                            var mm_t = String(today.getMonth() + 1).padStart(2, '0')
                            var yyyy_t = today.getFullYear()
                            var today_date = yyyy_t + '-' + mm_t + '-' + dd_t

                            var yesterday = new Date(today)
                            yesterday.setDate(yesterday.getDate() - 1)
                            var dd_y = String(yesterday.getDate()).padStart(2, '0')
                            var mm_y = String(yesterday.getMonth() + 1).padStart(2, '0')
                            var yyyy_y = yesterday.getFullYear()
                            var yesterday_date = yyyy_y + '-' + mm_y + '-' + dd_y
                            if (item.datasetIndex === 1) {
                                let time = item.label.split(' ')[1]
                                return item.yLabel + 'PPM' + ' ' + yesterday_date + ' ' + time
                            } else {
                                let time = item.label.split(' ')[1]
                                return item.yLabel + 'PPM' + ' ' + today_date + ' ' + time
                            }
                        },
                    },
                },
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
                                    minute: 'hh:00',
                                },
                            },
                            gridLines: {
                                display: false,
                            },
                            ticks: {
                                beginAtZero: true,
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
                            borderWidth: 2,
                        },
                        {
                            type: 'line',
                            mode: 'horizontal',
                            scaleID: 'y-axis-0',
                            value: 1000,
                            borderColor: 'rgb(255,192,0)',
                            borderWidth: 2,
                        },
                        {
                            type: 'line',
                            mode: 'horizontal',
                            scaleID: 'y-axis-0',
                            value: 800,
                            borderColor: 'blue',
                            borderWidth: 2,
                        },
                        {
                            type: 'line',
                            mode: 'horizontal',
                            scaleID: 'y-axis-0',
                            value: 500,
                            borderColor: 'green',
                            borderWidth: 2,
                        },
                    ],
                },
            },
        }
    },

    created() {
        this.getStartAndEndTime()
    },

    methods: {
        /**
         * @name getStartAndEndTime
         * @desc Get the starts_at and ends_at time in .env file
         *
         * @returns null
         */
        getStartAndEndTime() {
            axios
                .get('getStartAndEndTimeHotel')
                .then(response => {
                    this.starts_at = response.data['starts_at']
                    this.ends_at = response.data['ends_at']
                    this.getCo2ProcessData()
                })
                .catch(errors => {
                    console.log(errors)
                })
        },

        /**
         * @name getCo2ProcessData
         * @desc Get the process data of the netvox device
         *
         * @returns null
         */
        getCo2ProcessData() {
            axios
                .get('getNetvoxProcessedDataHotel/' + this.device_id)
                .then(response => {
                    var today = new Date()
                    var dd_t = String(today.getDate()).padStart(2, '0')
                    var mm_t = String(today.getMonth() + 1).padStart(2, '0')
                    var yyyy_t = today.getFullYear()
                    var today_date = yyyy_t + '-' + mm_t + '-' + dd_t

                    var yesterday = new Date(today)
                    yesterday.setDate(yesterday.getDate() - 1)
                    var dd_y = String(yesterday.getDate()).padStart(2, '0')
                    var mm_y = String(yesterday.getMonth() + 1).padStart(2, '0')
                    var yyyy_y = yesterday.getFullYear()
                    var yesterday_date = yyyy_y + '-' + mm_y + '-' + dd_y

                    this.co2_data_today.push({ x: today_date + ' ' + this.starts_at })

                    for (let i in response.data) {
                        if (response.data[i]['CREATED_AT'].split(' ')[0] == today_date) {
                            if (
                                response.data[i]['CREATED_AT'].split(' ')[1] >= this.starts_at &&
                                response.data[i]['CREATED_AT'].split(' ')[1] <= this.ends_at
                            ) {
                                if (response.data[i]['DATA']['co2']) {
                                    this.co2_data_today.push({
                                        x: response.data[i]['CREATED_AT'],
                                        y: response.data[i]['DATA']['co2'],
                                    })
                                }
                            }
                        } else if (response.data[i]['CREATED_AT'].split(' ')[0] == yesterday_date) {
                            if (
                                response.data[i]['CREATED_AT'].split(' ')[1] >= this.starts_at &&
                                response.data[i]['CREATED_AT'].split(' ')[1] <= this.ends_at
                            ) {
                                if (response.data[i]['DATA']['co2']) {
                                    this.co2_data_yesterday.push({
                                        x: today_date + ' ' + response.data[i]['CREATED_AT'].split(' ')[1],
                                        y: response.data[i]['DATA']['co2'],
                                    })
                                }
                            }
                        }
                    }

                    this.co2_data_today.push({ x: today_date + ' ' + this.ends_at })
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
                            data: this.getCo2DataToday,
                            backgroundColor: 'rgb(75, 192, 192)',
                            borderColor: 'rgb(75, 192, 192)',
                            pointBorderColor: 'rgb(75, 192, 192)',
                            fill: false,
                        },
                        {
                            label: '昨日のCO2 濃度',
                            data: this.getCo2DataYesterday,
                            backgroundColor: 'rgb(221,87,28)',
                            borderColor: 'rgb(221,87,28)',
                            pointBorderColor: 'rgb(221,87,28)',
                            fill: false,
                        },
                    ],
                },
                this.options
            )
        },
    },

    computed: {
        getCo2DataToday() {
            return this.co2_data_today
        },
        getCo2DataYesterday() {
            return this.co2_data_yesterday
        },
    },
    mounted() {
        /**
         * @desc Listen to NewDeviceDataEvent for new process data
         *
         * @returns null
         */
        Echo.channel('newdevice-data').listen('NewDeviceDataEvent', value => {
            this.getCo2ProcessData()
        })
    },
    beforeDestroy() {
        Echo.leave('newdevice-data')
    },
}
</script>
