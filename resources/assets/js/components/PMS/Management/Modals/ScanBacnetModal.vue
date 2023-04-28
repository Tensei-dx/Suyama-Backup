<!-- 
<System Name> iBMS
<Function Name> ScanBacnetModal.vue
<Create>            TP Uddin
<Update> 2020.05.27 TP Uddin Modify axios URL according to the URL list
 -->
<template>
	<!-- openmodal are props of vue contain the data from other component -->
	<!-- modal -->
	<div class="modal" :class="show">
		<!-- call close function -->
		<div class="modal-background" @click="closeModal"></div>
		<!-- modal dialog -->
		<div class="modal-dialog modal-dialog-centered" role="document">
			<!-- modal content -->
			<div class="modal-content scan-bg">
				<!-- modal body -->
				<div class="modal-body">
					<div v-if="showSpinner" class="m-3 text-center">
						<div class="spinner">
							<spinner :status="spinner.status"
									 :color="spinner.color"
									 :size="spinner.size"
									 :depth="spinner.depth"
									 :rotation="spinner.rotation"
									 :speed="spinner.speed"
									 ></spinner>
						</div>
						<h5 class="text-orange mt-4">
							{{ $t('gateway.scan') }} .......
						</h5>
						<div class="text-center mt-3">
							<a class="btn btn-secondary col-sm-4 text-white"
							   @click="closeModal"
							   >
								{{ $t('user.cancel') }}
							</a>
						</div>
					</div>
					<div v-else class="display-4 text-center">
						<div class="text-center">
							<i class="text-orange fa fa-check-circle fa-3x fa-fw"></i>
						</div>
						<h5 class="text-orange mt-4">
							{{ $t('gateway.scanComplete') }}
						</h5>
						<div class="text-center mt-3">
							<a class="btn btn-secondary col-sm-4 text-white"
							   @click="closeModal"
							   >
								{{ $t('user.save') }}
							</a>
						</div>
					</div>
				</div>
				<!-- modal body end -->
			</div>
			<!-- modal content end -->
		</div>
		<!-- modal dialod end -->
	</div>
	<!-- modal end -->
</template>

<script>
	export default {
		props: ['show', 'category', 'currentPage'],
		created() {
			this.getScan();
			$("body").addClass("modal-open");
		},
		data() {
			return {
				showSpinner: true,
				spinner: {
					size: 150,
					status: true,
					color: '#fd9500',
					depth: 15,
					rotation: true,
					speed: 0.7,
				}
			}
		},

		methods: {
			// Function name: getScan
			// Function description: scan bacnet devices 
			getScan() {
				axios.post("scanBacnetDevices", {
					'DEVICE_ID'			: this.device_id,
					'DEVICE_SERIAL_NO'	: this.device_serial
				})
				.then(response => {
					this.output = response.data;
					for (let i = 0; i <= 50; i++) {
						setTimeout(() => {
							this.showSpinner = false;
						}, 10*i)
					}
					this.$emit('loaddata', this.currentPage);
				})
				.catch(errors => {
					console.log(errors);
				});
			},
			// Function name: closeModal
			// Function description: closes scan bacnet modal
			closeModal() {
				this.$emit('loaddata', this.currentPage);
				this.$emit('close');
			},
		},
		// Function name: beforeDestroy
		// Function description: removes class for open modals
		beforeDestroy() {
			$("body").removeClass("modal-open");
		}
	};
</script>