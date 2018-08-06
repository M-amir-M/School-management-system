import {Bar} from 'vue-chartjs'


export default Bar.extend({
    data(){
        return {

        }
    },
    props: ['data','options'],
    mounted () {
        this.renderChart(this.data,this.options);
    }
})