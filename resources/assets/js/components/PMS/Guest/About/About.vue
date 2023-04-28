<template>
    <div class="row pt-1 pb-1 h-100 align-items-center">
        <div class="col-12">
            <p class="d-block text-white text-left font-weight-bold m-2">
                intelligent Building Management System (iBMS) for Hotel
            </p>
        </div>
        <div class="col-12">
            <div id="carouselBrochure" class="carousel slide" data-ride="carousel">
                <ol class="carousel-indicators">
                    <li data-target="#carouselBrochure" data-slide-to="0" class="active"></li>
                    <li data-target="#carouselBrochure" data-slide-to="1"></li>
                    <li data-target="#carouselBrochure" data-slide-to="2"></li>
                    <li data-target="#carouselBrochure" data-slide-to="3"></li>
                </ol>
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <img src="img/brochure/page_1.jpg" style="max-height: 500px;" type=button class="btn img-fluid" data-toggle="modal" data-target="#brochureModal">
                    </div>
                    <div class="carousel-item">
                        <img src="img/brochure/page_2.jpg" style="max-height: 500px;" type=button class="btn img-fluid" data-toggle="modal" data-target="#brochureModal">
                    </div>
                    <div class="carousel-item">
                        <img src="img/brochure/page_3.jpg" style="max-height: 500px;" type=button class="btn img-fluid" data-toggle="modal" data-target="#brochureModal">
                    </div>
                    <div class="carousel-item">
                        <img src="img/brochure/page_4.jpg" style="max-height: 500px;" type=button class="btn img-fluid" data-toggle="modal" data-target="#brochureModal">
                    </div>
                </div>
                <a v-if="this.pageNumber !== 1" href="#carouselBrochure" class="carousel-control-prev" role="button" data-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="sr-only">Previous</span>
                </a>
                <a v-if="this.pageNumber !== this.pageTotal" href="#carouselBrochure" class="carousel-control-next" role="button" data-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="sr-only">Next</span>
                </a>
            </div>
        </div>
        <div class="col-12">
            <span class="d-block text-right text-white m-1">
                Page {{ this.pageNumber }} of {{ this.pageTotal }}
            </span>
        </div>
        <!-- Modal -->
        <BrochureModal id="brochureModal" :pageNumber="this.pageNumber"></BrochureModal>
    </div>
</template>

<script>
import BrochureModal from '../Modal/BrochureModal.vue';
export default {
    components: {
        BrochureModal
    },
    data() {
        return {
            pageNumber: 1,
            pageTotal: 1,
        }
    },
    mounted() {
        var self = this;
        self.pageTotal = $('.carousel-item').length;
        $('#carouselBrochure')
            .carousel({
                interval: false,
                wrap: false,
                touch: true
            })
            .on('slide.bs.carousel', function (event) {
                self.pageNumber = event.to + 1;
            });
    },
}
</script>