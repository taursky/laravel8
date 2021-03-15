<template>
    <!--    ease="Expo.easeInOut"-->
    <vue-displacement-slideshow
        :images="images"
        displacement="/images/slider/9671768b65072ecd39b39f0cefb8ffab.png"
        :intensity="0.2"
        :speedIn="1.4"
        :speedOut="1.4"
        ease="expo.out"
        ref="slideshow"></vue-displacement-slideshow>
</template>

<script>

var slider = [];
if (window.images) {
    var slides = $.parseJSON(window.images);
    $.each(slides, function (key, value) {
        slider.push(value);
    });
}
import VueDisplacementSlideshow from "vue-displacement-slideshow";

export default {
    components: {
        VueDisplacementSlideshow,
    },
    computed: {
        images() {
            return slider;
        },
    },
    methods: {
        init() {
            //We loop through all our images by calling the 'next' method of our component every 2 seconds
            setInterval(() => {
                this.$refs.slideshow.next();
            }, 3000);
        }
    },
    mounted() {
        this.init();
    }
};

</script>
