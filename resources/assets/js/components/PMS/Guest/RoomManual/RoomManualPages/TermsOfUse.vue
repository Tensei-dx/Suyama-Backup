<template>
    <div class="row text-white px-2">
        <div class="col-sm-12 my-2 h3">
            {{$t('mobile.terms_of_usage.title')}}
        </div>
        <div class="col-md-12">
            <div class="card-body custom-bg-card-items text-left" style="overflow-y: auto; max-height: 540px;">
                <p class="text-justify">
                    {{$t('termsOfUse.introduction')}}
                </p>
                <b>{{$t('termsOfUse.items.title')}}</b>
                <ol>
                    <li>{{$t('termsOfUse.items.1')}}</li>
                    <li>{{$t('termsOfUse.items.2')}}</li>
                </ol>
                <b>{{$t('termsOfUse.matters.title')}}</b>
                <ol>
                    <li>{{$t('termsOfUse.matters.1')}}</li>
                    <li>{{$t('termsOfUse.matters.2')}}</li>
                </ol>
                <b>{{$t('termsOfUse.parking.title')}}</b>
                <ol>
                    <li>{{$t('termsOfUse.parking.1')}}</li>
                    <li>{{$t('termsOfUse.parking.2')}}</li>
                </ol>
                <b>{{$t('termsOfUse.hotelRules.title')}}</b>
                <ol>
                    <li>{{$t('termsOfUse.hotelRules.1')}}</li>
                    <li>{{$t('termsOfUse.hotelRules.2')}}</li>
                    <li>{{$t('termsOfUse.hotelRules.3')}}</li>
                </ol>
            </div>
            <!-- <div v-else-if="selectedLanguage == 'ja'" class="card-body custom-bg-card-items text-left" style="overflow-y: auto; max-height: 540px;">
                <p class="text-justify">
                    当コンテナハウスでは、お客様に安全かつ快適にご滞在いただくため、次の通り利用規則を定めておりますのでご協力くださいますようお願い申し上げます。
                </p>
                <b>〈火災予防上お守り頂きたい事項〉</b>
                <ol>
                    <li>火災の原因となりやすい場所（特にベット内）でご喫煙なさらないでください。</li>
                    <li>その他火災原因になるような行為をなさらないでください。</li>
                </ol>
                <b>〈保安上お守り頂きたい事項〉</b>
                <ol>
                    <li>宿泊者登録者のみでご宿泊をお願いいたします。</li>
                    <li>現金、貴重品はベッド下の金庫にて保管をお願いいたします。</li>
                </ol>
                <b>〈駐車場のご利用方法〉</b>
                <ol>
                    <li>駐車台数はお一人様１台とさせていただきます。</li>
                    <li>観光バスを除き、１台枠を超える車両の駐車は原則お断りします。</li>
                </ol>
                <b>ホテル内の規則</b>
                <ol>
                    <li>宿泊客が支払った期間が経過していない場合でも、第三者に部屋を譲り渡すことはできません。</li>
                    <li>ホテルにチェックインされていない方は、午前7時から午後10時までホテルの部屋にご宿泊いただけますが、午後10時以降にご宿泊の方はチェックインが必要です。</li>
                    <li>当ホテルは、前回の滞在中に、当ホテルまたは宿泊者の財産を毀損し、または他の宿泊者、当ホテルの従業員その他の宿泊者に損害を与え、
                        その他他の宿泊者の滞在または当ホテルの機能を侵害する行為により、ホテル規則に著しく違反した宿泊者の受け入れをお断りすることがあります。</li>
                </ol>
            </div> -->
        </div>
    </div>
</template>
<script>
export default {
data(){
    return{

    }
},
created(){

},
computed:{
    selectedLanguage: function() {
        return this.$i18n.locale;
    }
}
}
</script>
