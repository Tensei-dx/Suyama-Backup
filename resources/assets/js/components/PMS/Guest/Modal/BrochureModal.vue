<template>
    <div class="modal fade" id="brochureModal" tab-index="-1" aria-labelledby="brochureModal" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-xl">
                <div class="modal-content d-inline-block">
                    <div class="modal-body">
                        <div v-if="this.pageNumber == 1"><img src="img/brochure/page_1.jpg" class="img-fluid"></div>
                        <div v-else-if="this.pageNumber == 2"><img src="img/brochure/page_2.jpg" class="img-fluid"></div>
                        <div v-else-if="this.pageNumber == 3"><img src="img/brochure/page_3.jpg" class="img-fluid"></div>
                        <div v-else-if="this.pageNumber == 4"><img src="img/brochure/page_4.jpg" class="img-fluid"></div>
                    </div>
                </div>
            </div>
        </div>
</template>

<script>
export default {
    props: {
        pageNumber: Number
    }
}
</script>