import {Line} from 'vue-chartjs'


export default Line.extend({
    data(){
        return {

        }
    },
    props: ['data','options'],
    mounted () {
        this.renderChart(this.data,this.options);
    }
})