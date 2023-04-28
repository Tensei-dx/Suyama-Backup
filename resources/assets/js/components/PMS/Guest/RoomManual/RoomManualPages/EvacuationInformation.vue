<template>
    <div class="p-2">
        <span class="d-block font-weight-bold hotel-base-color" style="font-size: 2rem;">
            {{ $t('mobile.roomservice.evacuationRouteMap') }}
        </span>
        <div class="container mt-4" style="height: 480px;">
            <iframe :src="getMapPath()" width="100%" height="100%" style="border:0;" allowfullscreen=""
                    loading="lazy"></iframe>
        </div>
    </div>
</template>
<script>
export default {
    components: {},
    data() {
        return {
            map_path: {
                en: 'https://www.google.com/maps/embed?pb=!1m26!1m12!1m3!1d1614.487989609842!2d137.81961754267112!3d35.972012725863!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!4m11!3e2!4m5!1s0x601d1f9d6b4eb315%3A0xdfc9807279812cc4!2z6ZW36YeO55yM5aGp5bC75biC5aWI6Imv5LqV77yR77yT77yW77yXIENBUlJZIENVQkU!3m2!1d35.970091499999995!2d137.81831069999998!4m3!3m2!1d35.9730735!2d137.8236369!5e0!3m2!1sen!2sjp!4v1647567081898!5m2!1sen!2sjp',
                ja: 'https://www.google.com/maps/embed?pb=!1m26!1m12!1m3!1d1614.487989609842!2d137.81961754267112!3d35.972012725863!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!4m11!3e2!4m5!1s0x601d1f9d6b4eb315%3A0xdfc9807279812cc4!2z6ZW36YeO55yM5aGp5bC75biC5aWI6Imv5LqV77yR77yT77yW77yXIENBUlJZIENVQkU!3m2!1d35.970091499999995!2d137.81831069999998!4m3!3m2!1d35.9730735!2d137.8236369!5e0!3m2!1sja!2sjp!4v1647567081898!5m2!1sja!2sjp',
                ch_t: 'https://www.google.com/maps/embed?pb=!1m26!1m12!1m3!1d1614.487989609842!2d137.81961754267112!3d35.972012725863!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!4m11!3e2!4m5!1s0x601d1f9d6b4eb315%3A0xdfc9807279812cc4!2z6ZW36YeO55yM5aGp5bC75biC5aWI6Imv5LqV77yR77yT77yW77yXIENBUlJZIENVQkU!3m2!1d35.970091499999995!2d137.81831069999998!4m3!3m2!1d35.9730735!2d137.8236369!5e0!3m2!1sja!2sjp!4v1647567081898!5m2!1sja!2sjp',
                ko: 'https://www.google.com/maps/embed?pb=!1m26!1m12!1m3!1d1614.487989609842!2d137.81961754267112!3d35.972012725863!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!4m11!3e2!4m5!1s0x601d1f9d6b4eb315%3A0xdfc9807279812cc4!2z6ZW36YeO55yM5aGp5bC75biC5aWI6Imv5LqV77yR77yT77yW77yXIENBUlJZIENVQkU!3m2!1d35.970091499999995!2d137.81831069999998!4m3!3m2!1d35.9730735!2d137.8236369!5e0!3m2!1sja!2sjp!4v1647567081898!5m2!1sja!2sjp',
            },
        }
    },
    methods: {
        /**
         *
         */
        getMapPath() {
            switch (this.$i18n.locale) {
                case 'en':
                    return this.map_path.en
                case 'ja':
                    return this.map_path.ja
                case 'ko':
                    return this.map_path.ko
                case 'ch_t':
                    return this.map_path.ch_t
                default:
                    return this.map_path[process.env.MIX_DEFAULT_LOCALE]
                    break
            }
        },
    },
    watch: {},
}
</script>
