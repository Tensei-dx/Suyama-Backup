<!--
<System Name> iBMS
<Program Name> AddBacnetModal.vue
<Create>            TP Uddin
<Update> 2020.05.27 TP Uddin Modify axios URL according to the URL list
 -->
<template>
	<!-- cat,show are props of vue contain the data from other component -->
	<!-- bacnet device display -->
	<!-- check if the category is bacnet -->
	<div v-if="cat == 'bacnet'">
		<!-- modal -->
		<div class="modal" :class="show">
			<!-- call close function -->
			<div class="modal-background" @click="closeModal"></div>
			<!-- modal dialog -->
			<div class="modal-dialog modal-dialog-centered" role="document">
				<!-- modal content -->
				<div class="modal-content">
					<!-- modal header -->
					<div class="modal-header">
						<h5 class="modal-title text-dark">Add Bacnet Device</h5>
						<!-- call close function -->
						<button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="closeModal">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<!-- modal header end -->

					<!-- modal body -->
					<div class="modal-body text-dark">
						<div class="form-group">
							<label class="label">Floor</label>
							<div class="">
								<select
									class="custom-select"
									v-model="modalData.FLOOR_ID"
									disabled
									>
									<!-- loop the floors data -->
									<option
										v-for="floor,key in floors"
										:key="floor.FLOOR_ID"
										:value="floor.FLOOR_ID"
										:selected="floor.FLOOR_ID == modalData.FLOOR_ID"
										>
										{{ floor.FLOOR_NAME }}
									</option>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="label">Room</label>
							<div class="">
								<select class="custom-select" v-model="modalData.ROOM_ID" disabled>
									<!-- loop the rooms data -->
									<option
										v-for="room,key in rooms"
										:key="room.ROOM_ID"
										:value="room.ROOM_ID"
										:selected="room.ROOM_ID == modalData.ROOM_ID"
										>
										{{ room.ROOM_NAME }}
									</option>
								</select>
							</div>
						</div>
						<div class="form-group">
					    	<label class="label">Device Name</label>
			  				<input
			  					class="form-control"
			  					type="text"
			  					v-model="modalData.DEVICE_NAME"
			  					maxlength="15"
			  					@change="checkDeviceName"
			  					onkeyup="this.value=this.value.replace(/[^a-zA-Z0-9-_ ]/g, '')"
			  					placeholder="Device Name"
			  					required
			  					>
			  				<div v-if="this.deviceNameDuplication">
			  					<small class="text-danger">
			  						Device Name was already used
		  						</small>
			  				</div>
		  					<small
		  						class="text-danger"
		  						v-if="modalData.DEVICE_NAME.length < 3 || modalData.DEVICE_NAME.length >= 15"
		  						>
		  						Input should only contain 3 to 15 characters
		  					</small>
			  				<!-- display this when error occured -->
			  				<!-- <small v-if="errors.DEVICE_NAME" class="text-danger">{{ errors.DEVICE_NAME[0] }}</small> -->
						</div>
						<div class="form-group">
							<label class="label">Device Category</label>
							<div class="">
								<select
									class="custom-select"
									v-model="modalData.DEVICE_CATEGORY"
									@keyup.enter="addData(2)"
									>
									<!-- loop the rooms data -->
									<option
										v-for="type,key in deviceCAT"
										:key="type.ID"
										:value="type.ID"
										:selected="type.ID == modalData.DEVICE_CATEGORY"
										>
										{{ type.NAME }}
									</option>
								</select>
								<!-- <small v-if="errors.DEVICE_CATEGORY" class="text-danger"></small> -->
							</div>
						</div>

						<div class="form-group">
							<label class="label">Device Type</label>
							<div class="">
								<select
									class="custom-select"
									v-model="modalData.DEVICE_TYPE"
									@change="getObjectList()"
									@keyup.enter="addData(2)"
									:disabled="isLoading || processingObjects"
									>
									<!-- loop the rooms data -->
									<option
										v-for="dev,key in predefinedDevices"
										:key="dev.PRED_DEVICE_NAME"
										:value="dev.PRED_DEVICE_NAME"
										:selected="dev.PRED_DEVICE_NAME == modalData.DEVICE_TYPE"
										>
										{{ dev.PRED_DEVICE_NAME }}
									</option>
								</select>
								<!-- <small v-if="errors.DEVICE_TYPE" class="text-danger"></small> -->
							</div>
						</div>

						<div class="form-group">
							<label class="label">Select Objects:</label>
							<b-form-group>
								<b-form-checkbox-group
									v-model="selectedObjects"
									:options="options"
									name="objectnames"
									@change="atCheckboxChange"
									stacked
									:disabled="isLoading"
									>
								</b-form-checkbox-group>
							</b-form-group>
							<div>
								<b-button
									class="d-flex"
									:variant="buttonVariant"
									@click="validateObjects"
									:disabled="validateDisabled"
									>
									{{ validateButtonText }}
								</b-button>
							</div>
						</div>
					</div>
					<!-- modal body end -->

					<!-- modal footer -->
					<div class="modal-footer">
						<!-- call addData function -->
						<!-- enable/disable the save button using devSaveDisabled function -->
						<button
							class="btn btn-primary"
							@click="addData(2)"
							:disabled="devSaveDisabled"
							>
							<!-- display loading animation -->
							<span class="pull-left" v-if="loading">
								<i class="fa fa-spinner fa-pulse fa-1x fa-fw"></i>
							</span>
							<!-- change text value when click -->
							<span>{{ btn_text }}</span>
						</button>
						<button
							type="button"
							class="btn btn-secondary"
							@click="closeModal"
							>
							Close
						</button>
					</div>
					<!-- modal footer end -->
				</div>
				<!-- modal content end -->
			</div>
			<!-- modal dialog end -->
		</div>
		<!-- modal end -->
	</div>
	<!-- device display end -->
</template>

<script>
	export default {
		props: ['cat', 'show', 'currentPage', 'modalData'],
		created() {
			this.getFloors();
			$("body").addClass("modal-open");
			this.getDeviceList();
		},
		data() {
			return {
				floors: {},
				rooms: {},
				deviceCAT:[{"ID":0,"NAME":"Device"},{"ID":1,"NAME":"Sensor"}],
				predefinedDevices: {},
				selectedObjects: [],
				processingObjects: false,
				options: [],
				validation: "",
				isLoading: false,
				buttonVariant: 'secondary',
				validateButtonText: 'Validate',
				errors: {},
				loading: false,
				btn_text: 'Save',
				error_text: "This field is required",
				deviceNameDuplication: false
			}
		},

		methods: {
			// Function name: getFloors
			// Function description: Retrieve the floor of the gateway where the device is connected.
			getFloors() {
				axios.post('getFloorAll')
				.then(response => {
					this.floors = response.data;
					if (this.cat == 'bacnet') {
						this.getRooms();
					}
				})
				.catch(errors => {
					console.log(errors);
				});
			},
			// Function name: getRooms
			// Function description: Retrieve the romm of the gateway where the device is connected.
			getRooms() {
				axios.post('getFloorRooms/' + this.modalData.FLOOR_ID)
				.then(response => {
					this.rooms = response.data;
				})
				.catch(errors => {
					console.log(errors);
				});
			},
			// Function name: getDeviceList
			// Function description: Retrieve the list of predefined bacnet devices on the database.
			getDeviceList() {
				axios.get('getDevicesList')
				.then(response => {
					this.predefinedDevices = response.data;
				})
				.catch(errors => {
					console.log(errors);
				})
			},
			// Function name: getObjectList
			// Function description: Retrieve the list of objects of the selected predefined device.
			getObjectList() {
				this.processingObjects = true;
				this.options = [];
				this.selectedObjects = [];
				this.validation = "";
				this.buttonVariant = 'secondary';
				this.validateButtonText = 'Validate';
				axios.get('getObjectList/' + this.modalData.DEVICE_TYPE)
				.then(response => {
					for (var i = 0; i < response.data.length; i++) {
						this.options.push({text: response.data[i].DESCRIPTION, value: response.data[i].PRED_DEVICE_ID});
					}
					this.processingObjects = false;
				})
				.catch(errors => {
					console.log(errors);
					this.processingObjects = false;
				});
			},
			// Function name: atCheckboxChange
			// Function description: Reset validate button each time there is some changes in the selected checkbox
			atCheckboxChange() {
				this.buttonVariant = 'secondary';
				this.validation = '';
				this.validateButtonText = 'Validate';
			},
			// Function name: validateObjects
			// Function description: request for objectName and match it to the predefined object name
			validateObjects() {
				this.isLoading = true;
				this.validateButtonText = 'Validating';
				axios({
					method: 'post',
					url: 'validateBacnetObjects',
					data: {
						'selectedObjects' 	: this.selectedObjects,
						'deviceID' 			: this.modalData.DEVICE_ID
					}
				}).then(response => {
					if (response.data == 'True') {
						this.validation = true;
						this.isLoading = false;
						this.buttonVariant = 'success';
						this.validateButtonText = 'Validation Successful';
					} else if (response.data == 'False'){
						this.validation = false;
						this.isLoading = false;
						this.buttonVariant = 'danger';
						this.validateButtonText = 'Validation Failed';
					}
				}).catch(error => {
					this.catchErrors(error)
				});
			},
			// Function name: closeModal
			// Function description: closes the AddBacnetModal
			closeModal() {
				this.errors = {},
				this.loading = false,
				this.btn_text = 'Save',
				this.$emit('loaddata', this.currentPage),
				this.$emit('close')
			},
			// Function Name: catchErrors
			// Function description: catches and stores error
			catchErrors(error) {
				this.errors = error.response.data.errors,
				this.loading = false,
				this.btn_text = 'Save'
			},
			//
			//
			checkDeviceName() {
				this.deviceNameDuplication = false
			},
			// Function name: addData
			// Function description: send post request to register device
			// Param: key
			addData(key) {
				this.loading = true,
				this.btn_text = 'Saving';
				if (key == 2) {
					if (this.modalData.DEVICE_NAME < 3 || this.modalData.DEVICE_NAME > 15) {
						this.$toast.error(
	                        "Error",
	                        "Device Name should only contain 3 to 15 characters",
	                        {
	                            position:'topCenter'
	                        }
	                    );
					} else {
						axios({
							method: 'post',
							url: 'registerBacnetDevice',
							data: {
								'BACNETDEVICE_ID'	:this.modalData.BACNETDEVICE_ID,
								'FLOOR_ID' 			:this.modalData.FLOOR_ID,
								'ROOM_ID' 			:this.modalData.ROOM_ID,
								'DEVICE_NAME'		:this.modalData.DEVICE_NAME,
								'DEVICE_CATEGORY'	:this.modalData.DEVICE_CATEGORY,
								'DEVICE_TYPE' 		:this.modalData.DEVICE_TYPE,
								'selectedObjects' 	:this.selectedObjects
							}
						})
						.then(response => {
							if (response.data == 'success') {
								this.deviceNameDuplication = false;
								setTimeout(() => {
									this.closeModal();
								}, 1500);
							} else if (response.data == 'duplication') {
                                this.deviceNameDuplication = true;
                				this.loading = false,
								this.btn_text = 'Save'
							}
						})
						.catch(error => {
							this.catchErrors(error)
						});
					}
				}

			}
		},
		computed: {
			// Function name: roomDisabled
			// Function description: disable room select box if floor name is not filled yet.
			roomDisabled() {
				if (this.modalData.FLOOR_ID == null) {
					return true;
				} else {
					return false;
				}
			},
			// Function name: nameDisabled
			// Function description: disable device name input box if room name is not filled yet.
			nameDisabled() {
				if (this.modalData.ROOM_ID == null) {
					return true;
				} else {
					return false;
				}
			},
			// Function name: devSaveDisabled
			// Function description: disable save button if device type is not have been filled and validation is failed.
			devSaveDisabled() {
				if (this.modalData.DEVICE_TYPE && this.validation == true && this.modalData.DEVICE_NAME) {
					return false;
				} else {
					return true;
				}
			},
			// Function name: validateDisabled
			// Function description: disable validate button if device type is not have been filled.
			validateDisabled() {
				if (this.selectedObjects.length == 0 || this.isLoading == true || this.validation == true) {
					return true;
				} else {
					return false;
				}
			},

		},
		// Function name: beforeDestroy
		// Function description: removes class for open modals
		beforeDestroy() {
			$("body").removeClass("modal-open");
		}
	};
</script>