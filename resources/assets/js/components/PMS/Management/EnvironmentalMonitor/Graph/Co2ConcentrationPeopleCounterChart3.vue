<script>
import moment from 'moment'
import { Bar } from 'vue-chartjs'
import chartjsPluginAnnotation from 'chartjs-plugin-annotation'

export default {
    extends: Bar,

    props: {
        height: Number,
        width: Number,
        device_id: Number,
        room_id: Number,
    },

    data() {
        return {
            deviceData: [],
            co2_data: [],
            people_counter: [],
            camera_device_id: '',
            date_today: '',
            starts_at: '',
            ends_at: '',
            unit: 25,
            max_people: 0,
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
                            id: 'co2-y-axis',
                            type: 'linear',
                            position: 'left',
                            ticks: {
                                min: 0,
                                max: 3000,
                                stepSize: 500,
                                // = SPRINT_04 TASK127
                                fontSize: 15,
                                // = SPRINT_04 TASK127
                                beginAtZero: true,
                                fontColor: 'rgb(75, 192, 192)',
                                padding: 10,
                            },
                            scaleLabel: {
                                display: true,
                                labelString: 'PPM',
                                fontColor: 'rgb(75, 192, 192)',
                            },
                            gridLines: {
                                display: false,
                            },
                        },
                        {
                            id: 'people-y-axis',
                            type: 'linear',
                            position: 'right',
                            ticks: {
                                min: 0,
                                max: 25,
                                stepSize: 5,
                                fontSize: 15,
                                beginAtZero: true,
                                fontColor: 'rgb(137,49,1)',
                                padding: 10,
                            },
                            scaleLabel: {
                                display: true,
                                labelString: 'People',
                                fontColor: 'rgb(137,49,1)',
                            },
                            gridLines: {
                                display: false,
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
                        },
                    ],
                },
                annotation: {
                    annotations: [
                        {
                            type: 'line',
                            mode: 'horizontal',
                            scaleID: 'co2-y-axis',
                            value: 2500,
                            borderColor: 'red',
                            borderWidth: 2,
                        },
                        {
                            type: 'line',
                            mode: 'horizontal',
                            scaleID: 'co2-y-axis',
                            value: 1000,
                            borderColor: 'rgb(255,192,0)',
                            borderWidth: 2,
                        },
                        {
                            type: 'line',
                            mode: 'horizontal',
                            scaleID: 'co2-y-axis',
                            value: 800,
                            borderColor: 'blue',
                            borderWidth: 2,
                        },
                        {
                            type: 'line',
                            mode: 'horizontal',
                            scaleID: 'co2-y-axis',
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
        this.date_today = moment().format('YYYY-MM-DD')
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
                    this.getCameraDeviceId()
                    this.getCo2ProcessDataToday()
                })
                .catch(errors => {
                    console.log(errors)
                })
        },

        /**
         * @name getCameraDeviceId
         * @desc Get the DEVICE_ID of the camera in the room
         *
         * @returns null
         */
        getCameraDeviceId() {
            axios
                .get('getRoomNetvoxDevicesHotel/' + this.room_id)
                .then(response => {
                    for (let i in response.data) {
                        if (response.data[i]['DEVICE_TYPE'] == 'camera') {
                            this.camera_device_id = response.data[i]['DEVICE_ID']
                            this.getCameraPeopleCounterProcessDataToday()
                        }
                    }
                })
                .catch(errors => {
                    console.log(errors)
                })
        },

        /**
         * @name getCameraPeopleCounterProcessDataToday
         * @desc Get the process data of the camera
         *
         * @returns null
         */
        getCameraPeopleCounterProcessDataToday() {
            axios
                .get('getNetvoxProcessedDataHotel/' + this.camera_device_id)
                .then(response => {
                    this.deviceData = response.data.filter(item => {
                        const TODAY = moment().format('YYYY-MM-DD')
                        const STARTS_AT = moment(TODAY + ' ' + this.starts_at).format('YYYY-MM-DD HH:mm:ss')
                        const ENDS_AT = moment(TODAY + ' ' + this.ends_at).format('YYYY-MM-DD HH:mm:ss')

                        return moment(item.CREATED_AT).isBetween(STARTS_AT, ENDS_AT)
                    })

                    this.people_counter = this.deviceData.map(item => {
                        const x = item.CREATED_AT
                        const y = item.DATA.peopleIn - item.DATA.peopleOut
                        if (this.max_people < y) {
                            this.max_people = y
                        }

                        return { x, y }
                    })
                    this.options.scales.yAxes[1].ticks.max = (this.max_people / this.unit + 1) * this.unit
                })
                .then(response => this.getCo2ProcessDataToday())
                .catch(errors => {
                    console.log(errors)
                })
        },

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
                    this.co2_data.push({ x: this.date_today + ' ' + this.starts_at })
                    for (let i in response.data) {
                        if (response.data[i]['CREATED_AT'].split(' ')[0] == this.date_today) {
                            if (
                                response.data[i]['CREATED_AT'].split(' ')[1] >= this.starts_at &&
                                response.data[i]['CREATED_AT'].split(' ')[1] <= this.ends_at
                            ) {
                                if (response.data[i]['DATA']['co2']) {
                                    this.co2_data.push({
                                        x: response.data[i]['CREATED_AT'],
                                        y: response.data[i]['DATA']['co2'],
                                    })
                                }
                            }
                        }
                    }
                    this.co2_data.push({ x: this.date_today + ' ' + this.ends_at })
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
                            type: 'line',
                            data: this.getCo2Data,
                            backgroundColor: 'rgb(75, 192, 192)',
                            borderColor: 'rgb(75, 192, 192)',
                            pointBorderColor: 'rgb(75, 192, 192)',
                            yAxisID: 'co2-y-axis',
                            fill: false,
                        },
                        {
                            label: '人数',
                            type: 'bar',
                            data: this.getPeopleCounterData,
                            backgroundColor: 'rgb(137,49,1)',
                            borderColor: 'rgb(137,49,1)',
                            pointBorderColor: 'rgb(137,49,1)',
                            id: 'people-counter-label',
                            yAxisID: 'people-y-axis',
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
        getPeopleCounterData() {
            return this.people_counter
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
