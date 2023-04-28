<template>
    <div class="row">
        <div v-if="loading == false && renderedData.length > 0" class="col-md-12 mb-1">
            <b-table class="audittrailslogs_table">
            </b-table>
            <b-table small  v-if="renderedData"
                            :fields="table.fields"
                            :items="renderedData"
                            :per-page="table.perPage"
                            :current-page="table.currentPage"
                            :filter="searchText">
                <template slot="INSTRUCTION" slot-scope="data">
                    <div v-b-tooltip.hover :title="data.item.INSTRUCTION">
                        {{ checkTextSize(data.item.INSTRUCTION) }}
                    </div>
                </template>
            </b-table>
        </div>
        <div v-else-if="loading == false && renderedData.length == 0" class="logs-div h-100">
            <!-- <div>{{$t('logs.noData')}}</div> -->
            <div>
                NO AUDIT LOGS AVAILABLE
            </div>
        </div>
        <div v-else class="logs-div">
            <i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i>
        </div>
        <div class="col-md-12 text-truncate">
            <div class="d-flex justify-content-between" :class="customPagination">
                <div></div>
                <b-pagination class="pl-5" :total-rows="table.totalRows" :per-page="table.perPage" v-model="table.currentPage">
                </b-pagination>
                <div v-if="!loading && renderedData.length > 0">
                    <button id="exportAuditBtn" class="btn btn-primary btn-sm mr-3">
                        {{$t('logs.export')}}
                    </button>
                    <b-popover target="exportAuditBtn" placement="top" :title="$t('logs.chooseFormat')" triggers="focus">
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
    var jsPDF = require('jspdf');
    require('jspdf-autotable');
    export default{
        components:{
        },
        props:{
            locale:'',
            searchText:'',
            startDate:'',
            endDate:'',
            user:'',
        },
        data(){
            return{
                table:{
                    fields:[
                        {key:'AUDIT_LOGS_ID',label:'ID'},
                        {key:'IP',label:'IP'},
                        {key:'HOST',label:'Host',formatter:'host'},
                        {key:'MODULE',label:'Module'},
                        {key:'INSTRUCTION',label:'Instruction'},
                        {key:'CREATED_AT',label:'Date'},
                    ],
                    totalRows:0,
                    perPage:10,
                    currentPage:1
                },
                logs:'',
                renderedData:'',
                showExportIcon:false,
                loading:true
            };
        },
        created(){
            this.getAuditLogs();
        },
        mounted(){
            this.changeTable();
        },
        methods:{
            //Function Name: getAuditLogs
            //Function Description: Get all Audit Logs
            getAuditLogs(){
                axios.post('getAuditLogs', {
                    'START_DATE':this.startDate,
                    'END_DATE':this.endDate
                })
                .then(response=>{
                    this.logs = response.data;
                    this.renderedData = this.logs;
                    this.table['totalRows'] = response.data.length;
                    this.loading = false;
                });
            },
            //Function Name: checkTextSize
            //Function Description: Checks if text is more than 90 characters long and will shorten to fit into table
            //Param: content ('string')
            checkTextSize(content){
                if(content && content.length > 10){
                    content = content.substring(0,10) + "...";
                }
                return content;
            },
            //Function Name: exportCSV
            //Function Description: Create a csv file and download on click
            //Param: rederedData (logs)
            exportCSV: function () {
                window.open("/report_logs/Report_Audit_Logs_CSV.zip");
            },
            //Function Name: exportPDF
            //Function Description: Export pdf file
            exportPDF(){
                window.open("/report_logs/Report_Audit_Logs_PDF.zip");
            },
            //Function Name: processexportPDF
            //Function Description: Generate pdf file to be exported
            //Param: renderedData (logs)
            processexportPDF(){
                var vm = this;
                if (vm.renderedData.length <= 50000) {
                    try{
                        var username = this.user.USERNAME;
                        // var companyLogo = new Image;
                        // var companyName = "RS Hospital Audit Trail Logs Report";        //Comapny Name
                        // companyLogo.src = "img/gti-logo.png";                           //Company Logo
                        var columns = [
                                        {title:"ID", dataKey:"AUDIT_LOGS_ID"},
                                        {title:"Type", dataKey:"HOST"},
                                        {title:"Module", dataKey:"MODULE"},
                                        {title:"Instruction Type", dataKey:"INSTRUCTION"},
                                        {title:"IP", dataKey:"IP"},
                                        {title:"Date",dataKey:"CREATED_AT"}
                                        ];
                        var doc = new jsPDF({
                            orientation:'landscape',
                            format:'legal'
                        });
                        var totalPagesExp = "{total_pages_count_string}";
                        var pageWidth = doc.internal.pageSize.width || doc.internal.pageSize.getWidth();
                        var columnStyles = {
                                            AUDIT_LOGS_ID:{     columnWidth:20},
                                            HOST:{              columnWidth:30},
                                            MODULE:{            columnWidth:''},
                                            INSTRUCTION:{       columnWidth:80},
                                            IP:{                columnWidth:30},
                                            CREATED_AT:{        columnWidth:''}
                                            }
                        var pageContent = function(data){
                            //HEADER
                            doc.setFontSize(15);
                            doc.setTextColor(40);
                            doc.setFontStyle('normal');
                            doc.addImage(companyLogo,data.settings.margin.left,10,15,15);
                            doc.text(companyName,(pageWidth / 2) -145, 20);
                            // FOOTER
                            var str = "Page " + data.pageCount;
                            // Total page number plugin only available in jspdf v1.0+
                            if (typeof doc.putTotalPages === 'function') {
                                str = str + " of " + totalPagesExp;
                            }
                            doc.setFontSize(8);
                            var pageHeight = doc.internal.pageSize.height || doc.internal.pageSize.getHeight();
                            doc.text("Author: " + username, data.settings.margin.left, pageHeight  - 15);
                            doc.text("Date Generated: " + vm.timestamp, data.settings.margin.left, pageHeight  - 10);
                            doc.text(str, (pageWidth / 2) - 20, pageHeight  - 10);
                        };
                        doc.autoTable(columns, vm.renderedData, {
                            addPageContent: pageContent,
                            margin: {top: 30},
                            theme: 'grid',
                            columnStyles:columnStyles
                        });
                        if(typeof doc.putTotalPages === 'function'){
                            doc.putTotalPages(totalPagesExp);
                        }
                        doc.save(vm.exportFilename);
                        this.showExportIcon = false;
                        axios.post('downloadLog', {
                            filetype:"PDF",
                            log:'System Logs'
                        })
                        .then(response=>{
                        })
                        .catch(error=>{
                            let errormessage = error.response.data.file + " : " + error.response.data.message;
                            axios.post('createSystemLogs',{ERROR_MESSAGE:errormessage});
                        });
                    }catch(error) {
                        if(error.message.includes("UNKNOWN")){
                            vm.$refs.pdf.click();
                        }else{
                            let message = this.$t('logs.error');
                            this.$swal({
                                position: 'center',
                                type: 'error',
                                title: message,
                                showConfirmButton: false,
                                timer: 2000,
                            });
                            this.showExportIcon = false;
                        }
                    }
                }else{
                    let tooMany = this.$t('logs.rows');
                    this.$swal({
                    position: 'center',
                    type: 'error',
                    title: tooMany,
                    showConfirmButton: false,
                    timer: 2000,
                    });
                    this.showExportIcon = false;
                }
            },
            //Function Name: changeTable
            //Function Description: change table label according to language settings
            //param: $i18n.locale
            changeTable(){
                var labels = this.table.fields;
                var messages = this.$t('logs.auditTable');
                for (var i in labels){
                    Object.keys(messages).forEach(function(mess){
                        if (labels[i].key == mess) {
                            labels[i].label = messages[mess];
                        }
                    });
                }
                this.table.fields = labels;
                if (this.$children[2]) {
                    this.$children[2].refresh();
                }
            },
            host(value) {
                return value;
            }
        },
        computed:{
            // Change pagination interface if too many data.
            customPagination:function(){
                if(this.renderedData.length <= 60){
                    return 'custom-pagination-white';
                }
            },
            timestamp: function () {
                return this.$moment().format('MM/DD/YYYY HH:mm:ss');
            },
            exportFilename:function(){
                return 'logs_' + this.$moment().format('MMDDYYYYHHmmss');
            }
        },
        watch:{
            locale:function(){
                this.changeTable();
            },
            endDate:function(){
                this.getAuditLogs();
            },
            startDate:function(){
                this.getAuditLogs();
            },
            renderedData:function(){
                this.table['totalRows'] = this.renderedData.length;
            },
            searchText:function(){
                this.$forceUpdate();
            }
        }

    };
</script>
// + SPRINT_08 TASK149
<style>
/* .audittraillogs_table { */
  /* max-height: 100px; */
  /* overflow-y: auto;
  text-overflow: ellipsis;
  width: 85%;
  display: inline-block; */
/* } */
.logs-div{
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
// + SPRINT_08 TASK149
