<!--  
    <System Name> iBMS
    <Program Name> SummaryModal.vue
    <Create>             TP Robert
    <Update> 2019.07.11  TP Ivin Insert Comment header
             2020.05.27  TP Uddin Modify axios URL according to the URL list
-->
<template>
	<transition name="fade">
        <div class="modal d-block">
            <div class="modal-background" @click="closeModal()"></div>
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">{{modalData[0].title}}</h5>
                        <button type="button" class="close" @click="closeModal()" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body h-655 custom-scroll-bar">
                        <b-table v-if="status !== 'onlinedevices'" outlined hover :items="items" class="cursor-default"></b-table>
                        <b-table v-if="status == 'onlinedevices'" outlined hover :items="items" :fields="fields">
                            <template slot="show_devices" slot-scope="row">
                                <button @click.stop="row.toggleDetails" class="btn btn-sm btn-primary">
                                    Show Devices
                                </button>
                            </template>
                            <template slot="row-details" slot-scope="row">
                                <div v-if="row.item.Devices.length > 0">
                                    <b-table :items="row.item.Devices" :fields="deviceFields"></b-table>
                                </div>
                                <div v-else>No Online Devices in the Room</div>
                            </template>
                        </b-table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="closeModal()" >Close</button>
                    </div>
                </div>
            </div>
        </div>
	</transition>
</template>
<script type="text/javascript">
	export default{
		props:['modalData','status', 'locale'],
		data(){
			return{
                items: this.modalData[1].data,
                fields:[
                    {key:'Room', label:'Room'},
                    {key:'Count', label:'Count'},
                    {key:'show_devices', label:'Devices'},
                ],
                deviceFields:[
                    {key:'DEVICE_NAME', label:'Device Name'},
                    {key:'DEVICE_TYPE', label:'Device Type'},
                ],
			}
		},
		created(){
            $("body").addClass("modal-open");
		},
        mounted(){
            this.changeTableLabel();
        },
		methods:{
            //Function Name: closeModal
            //Function Description: Closes all modals on click of close button
			closeModal(){
				this.$emit("closeModal");
			},
            //Function Name: changeTableLabel
            //Function Description: Changes table labels according to language settings
            changeTableLabel(){
                var messages = this.$t('dashboard.summaryLabels');
                var fields = this.fields;
                var devFields = this.deviceFields;
                if (this.status == 'onlinedevices') {
                    for(var i in fields){
                        Object.keys(messages).forEach(function(message){
                            if (fields[i].key == message) {
                                fields[i].label = messages[message];
                            }
                        });
                    }
                    this.fields = fields
                    for (var j in devFields){
                        Object.keys(messages).forEach(function(mess){
                            if (devFields[j].key == mess) {
                                devFields[j].label = messages[mess];
                            }
                        });
                    }
                    this.deviceFields = devFields;
                    if (this.$children[0]) {
                        this.$children[0].refresh();
                    }
                }
            }
		},
        watch:{
            locale:function(){
                this.changeTableLabel();
            }
        },
		beforeDestroy() {
            $("body").removeClass("modal-open");
		}
	}
</script>