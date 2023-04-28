<!--
    <System Name> iBMS
    <Program Name> SystemLogs.vue
    <Create>            TP Harvey
    <Update> 2019.7.11  TP Ivin Insert Comment header
             2020.05.27 TP Uddin Modify axios URL according to the URL list
             2021.09.22 TP Jermaine SPRINT_08 TASK149
-->
<template>
    <div class="row">
        <!-- = SPRINT_08 TASK149 -->
        <!-- <div v-if="loading == false && renderedData.length > 0" class="col-md-12 h-725 mb-1"> -->
        <div v-if="loading == false && renderedData.length > 0" class="col-md-12 mb-1">
            <!-- = SPRINT_08 TASK149 -->
            <!-- + SPRINT_08 TASK149 -->
            <b-table class="systemlogs_table">
            </b-table>
            <!-- + SPRINT_08 TASK149 -->
            <b-table small sortable v-if="renderedData" :fields="table.fields" :items="renderedData"
                     :per-page="table.perPage" :current-page="table.currentPage" :filter="searchText">
                <template slot="CONTENT" slot-scope="data">
                    <div v-b-tooltip.hover :title="data.item.CONTENT">
                        {{ checkTextSize(data.item.CONTENT) }}
                    </div>
                </template>
            </b-table>
        </div>
        <div v-else-if="loading == false && renderedData.length == 0" class="logs-div h-100">
            <div>{{$t('logs.noData')}}</div>
        </div>
        <div v-else class="logs-div">
            <i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i>
        </div>
        <!-- = SPRINT_08 TASK149 -->
        <!-- <div class="col-md-12"> -->
        <div class="col-md-12 text-truncate">
            <!-- = SPRINT_08 TASK149 -->
            <div class="d-flex justify-content-between" :class="customPagination">
                <div></div>
                <b-pagination class="pl-5" :total-rows="table.totalRows" :per-page="table.perPage"
                              v-model="table.currentPage">
                </b-pagination>
                <div v-if="!loading && renderedData.length > 0">
                    <button id="exportLogsBtn" class="btn btn-primary btn-sm mr-3">
                        {{$t('logs.export')}}
                    </button>
                    <b-popover target="exportLogsBtn" placement="top" :title="$t('logs.chooseFormat')" triggers="focus">
                        <div class='d-flex justify-content-around'>
                            <button @click='exportPDF' type='button' class='btn btn-danger btn-sm'>PDF</button>
                            <spinner v-if="showExportIcon"></spinner>
                            <button @click='exportCSV' type='button' class='btn btn-success btn-sm'>CSV</button>
                        </div>
                    </b-popover>
                </div>
            </div>
        </div>
    </div>
</template>
<script type="text/javascript">
var jsPDF = require('jspdf')
require('jspdf-autotable')
export default {
    components: {},
    props: {
        locale: '',
        searchText: '',
        startDate: '',
        endDate: '',
        user: '',
    },
    data() {
        return {
            table: {
                fields: [
                    { key: 'LOGS_ID', label: 'ID' },
                    { key: 'TYPE', label: 'Type' },
                    { key: 'INSTRUCTION_TYPE', label: 'Instruction Type' },
                    { key: 'CONTENT', label: 'Content' },
                    { key: 'HOST', label: 'Sender' },
                    { key: 'CREATED_AT', label: 'Date' },
                ],
                totalRows: 0,
                perPage: 10,
                currentPage: 1,
            },
            logs: '',
            renderedData: '',
            showExportIcon: false,
            loading: true,
            userdata: '',
        }
    },
    created() {
        this.getUser()
    },
    mounted() {
        this.changeTable()
    },
    methods: {
        //Function Name: getUser
        //Function Description: Get User
        //Param: user_id, user_status, user_type
        getUser() {
            axios
                .post('getUser', { USER_ID: this.user })
                .then(response => {
                    if (typeof response.data == 'object') {
                        this.userdata = response.data
                        this.getLogs()
                    }
                })
                .catch(errors => {
                    console.log(errors)
                })
        },
        //Function Name: getLogs
        //Function Description: Retrieves logs based on user
        //Param: user_id
        getLogs() {
            //get first 10 to load first batch of data then load the rest
            let url = ''
            let url2 = ''
            if (this.userdata.USER_TYPE == 1) {
                url = 'getSystemLogs?LIMIT=10'
                url2 = 'getSystemLogs'
            } else {
                url = 'getUserLogs?LIMIT=10'
                url2 = 'getUserLogs'
            }
            axios
                .post(url, {
                    START_DATE: this.startDate,
                    END_DATE: this.endDate,
                })
                .then(response => {
                    console.warn(response)
                    if (typeof response.data == 'object') {
                        this.logs = response.data
                        this.renderedData = this.logs
                        this.loading = false
                        axios
                            .post(url2, {
                                START_DATE: this.startDate,
                                END_DATE: this.endDate,
                            })
                            .then(response => {
                                if (typeof response.data == 'object') {
                                    this.logs = response.data
                                    this.renderedData = this.logs
                                    this.table['totalRows'] = response.data.length
                                } else {
                                    let retError = this.$t('error_message_code.ERR_OPS_051')
                                    this.$swal({
                                        position: 'center',
                                        type: 'error',
                                        title: retError,
                                        showConfirmButton: false,
                                        timer: 2000,
                                    })
                                }
                            })
                    } else {
                        let retError = this.$t('error_message_code.ERR_OPS_051')
                        this.$swal({
                            position: 'center',
                            type: 'error',
                            title: retError,
                            showConfirmButton: false,
                            timer: 2000,
                        })
                    }
                })
        },
        //Function Name: checkTextSize
        //Function Description: Checks if text is more than 90 characters long and will shorten to fit into table
        //Param: content ('string')
        checkTextSize(content) {
            if (content && content.length > 50) {
                content = content.substring(0, 50) + '...'
            }
            return content
        },
        //Function Name: exportCSV
        //Function Description: Create a csv file and download on click
        //Param: renderedData (logs)
        exportCSV: function () {
            window.open('/report_logs/Report_System_Logs_CSV.zip', '_blank')
        },
        //Function Name: exportPDF
        //Function Description: Export pdf file
        exportPDF() {
            window.open('/report_logs/Report_System_Logs_PDF.zip', '_blank')
        },
        //Function Name: processexportPDF
        //Function Description: generate pdf for export
        //Param: renderedData (logs), username
        processexportPDF() {
            this.showExportIcon = true
            var vm = this
            if (vm.renderedData.length <= 50000) {
                try {
                    var username = this.user.USERNAME
                    // var companyLogo = new Image;
                    // var companyName = "RS Hospital System Logs Report";     //Comapny Name
                    // companyLogo.src = "img/gti-logo.png";                           //Company Logo
                    var columns = [
                        { title: 'ID', dataKey: 'LOGS_ID' },
                        { title: 'Type', dataKey: 'TYPE' },
                        { title: 'Instruction Type', dataKey: 'INSTRUCTION_TYPE' },
                        { title: 'Content', dataKey: 'CONTENT' },
                        { title: 'IP', dataKey: 'IP' },
                        { title: 'Date', dataKey: 'CREATED_AT' },
                    ]

                    var doc = new jsPDF({
                        orientation: 'landscape',
                        format: 'legal',
                    })

                    var totalPagesExp = '{total_pages_count_string}'
                    var pageWidth = doc.internal.pageSize.width || doc.internal.pageSize.getWidth()
                    var columnStyles = {
                        LOGS_ID: { columnWidth: 15 },
                        TYPE: { columnWidth: 15 },
                        INSTRUCTION_TYPE: { columnWidth: 32 },
                        CONTENT: { columnWidth: '' },
                        IP: { columnWidth: 30 },
                        CREATED_AT: { columnWidth: 40 },
                    }
                    var pageContent = function (data) {
                        //HEADER
                        doc.setFontSize(15)
                        doc.setTextColor(40)
                        doc.setFontStyle('normal')
                        doc.addImage(companyLogo, data.settings.margin.left, 10, 15, 15)
                        doc.text(companyName, pageWidth / 2 - 145, 20)
                        // FOOTER
                        var str = 'Page ' + data.pageCount
                        // Total page number plugin only available in jspdf v1.0+
                        if (typeof doc.putTotalPages === 'function') {
                            str = str + ' of ' + totalPagesExp
                        }
                        doc.setFontSize(10)
                        var pageHeight = doc.internal.pageSize.height || doc.internal.pageSize.getHeight()
                        doc.text('Author: ' + username, data.settings.margin.left, pageHeight - 15)
                        doc.text('Date Generated: ' + vm.timestamp, data.settings.margin.left, pageHeight - 10)
                        doc.text(str, pageWidth / 2 - 20, pageHeight - 10)
                    }
                    doc.autoTable(columns, vm.renderedData, {
                        addPageContent: pageContent,
                        margin: { top: 30 },
                        theme: 'grid',
                        columnStyles: columnStyles,
                    })
                    if (typeof doc.putTotalPages === 'function') {
                        doc.putTotalPages(totalPagesExp)
                    }
                    doc.save(vm.exportFilename)
                    this.showExportIcon = false
                    axios
                        .post('downloadLog', {
                            filetype: 'PDF',
                            log: 'System Logs',
                        })
                        .then(response => {
                            console.log('success')
                        })
                        .catch(error => {
                            let errormessage = error.response.data.file + ' : ' + error.response.data.message
                            axios.post('createSystemLogs', { ERROR_MESSAGE: errormessage })
                        })
                } catch (error) {
                    if (error.message.includes('UNKNOWN')) {
                        vm.$refs.pdf.click()
                    } else {
                        let message = this.$t('logs.error')
                        this.$swal({
                            position: 'center',
                            type: 'error',
                            title: message,
                            showConfirmButton: false,
                            timer: 2000,
                        })
                        console.log(error)
                        this.showExportIcon = false
                    }
                }
            } else {
                let tooMany = this.$t('logs.rows')
                this.$swal({
                    position: 'center',
                    type: 'error',
                    title: tooMany,
                    showConfirmButton: false,
                    timer: 2000,
                })
                this.showExportIcon = false
            }
        },
        //Function Name: changeTable
        //Function Description: change table label according to language settings
        //param: $i18n.locale
        changeTable() {
            var labels = this.table.fields
            var messages = this.$t('logs.systemTable')
            for (var i in labels) {
                Object.keys(messages).forEach(function (mess) {
                    if (labels[i].key == mess) {
                        labels[i].label = messages[mess]
                    }
                })
            }
            this.table.fields = labels
            if (this.$children[2]) {
                this.$children[2].refresh()
            }
        },
    },
    computed: {
        // Change pagination interface if too many data.
        customPagination: function () {
            if (this.renderedData.length <= 60) {
                return 'custom-pagination-white'
            }
        },
        //return current date
        timestamp: function () {
            return this.$moment().format('MM/DD/YYYY HH:mm:ss')
        },
        //returns file name with attached date
        exportFilename: function () {
            return 'logs_' + this.$moment().format('MMDDYYYYHHmmss')
        },
    },
    watch: {
        locale: function () {
            this.changeTable()
        },
        //filter data on change of end date
        endDate: function () {
            this.getLogs()
        },
        //filter data on change of start date
        startDate: function () {
            this.getLogs()
        },
        //change total rows to update table pagination on logs update
        renderedData: function () {
            this.table['totalRows'] = this.renderedData.length
        },
        //search function
        searchText: function () {
            this.$forceUpdate()
        },
    },
}
</script>
//  + SPRINT_08 TASK149
<style>
/* .systemlogs_table {
  /* max-height: 100px; */
/* overflow-y: auto;
  text-overflow: ellipsis;
  width: 85%; */
/* display: inline-block; */
/* }  */
.logs-div {
    height: 400px;
    width: 850px;
    position: relative;
    margin-top: inherit;
    margin-bottom: auto;
    margin-left: auto;
    margin-right: auto;
    top: 0;
    bottom: 0;
    left: 0;
    right: 0;
}
</style>
//  + SPRINT_08 TASK149
