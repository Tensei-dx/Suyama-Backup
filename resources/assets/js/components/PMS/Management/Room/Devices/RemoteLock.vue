<template>
    <div class="card pointer" @click="$bus.emit('onSelectDeviceItem', device)">
        <div class="no-gutters align-items-center">
            <div class="h-100 py-1 text-center hotel-base-color rounded-left">
                <span class="d-block pt-2 pb-2">
                    <i class="fa fa-key fa-lg text-black fa-2x" aria-hidden="true"></i>
                </span>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        device: Object,
    },
}
</script>

<style>
</style>
