import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/vue3'
import VueEasyLightbox from 'vue-easy-lightbox'
import Maska from 'maska'
import moment from 'moment'

createInertiaApp({
  progress: {
    color: '#ff0000',
  },
  resolve: name => require(`./Pages/${name}`),
  setup({ el, App, props, plugin }) {
    createApp({ render: () => h(App, props) })
      //set mixins
      .mixin({
        methods: {
          //method "hasAnyPermission"
          hasAnyPermission: function (permissions) {

            //get permissions from props
            let allPermissions = this.$page.props.auth.permissions;

            let hasPermission = false;
            permissions.forEach(function(item){
              if(allPermissions[item]) hasPermission = true;
            });

            return hasPermission;
          },

          //format price
          formatPrice(value) {
            let val = (value/1).toFixed(0).replace('.', ',')
            return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")
          },
          changeDateFormat(value){
            moment.locale('id')
            return moment(new Date(value)).format('dddd, D MMMM Y')
          },
          changeDateTimeFormat(value){
            moment.locale('id')
            return moment(new Date(value)).format('dddd, D MMMM Y HH:mm:ss')
          },
          countAge(date){
            const age = moment().diff(moment(date), 'years');
            return age + ' years old';
          },
          getDate(){
            moment.locale('id')
            const date = new Date()
            return moment(new Date(date)).format('dddd, D MMMM Y')
          },
          getTime(){
            moment.locale('id')
            const date = new Date()
            return moment(new Date(date)).format('HH:mm')
          },
          formatNumber(value) {
            return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")
          },

          //title
          title() {
            return "TSP"
          },

        },
      })
      .use(plugin)
      .use(VueEasyLightbox)
      .use(Maska)
      .mount(el)
  },
});
