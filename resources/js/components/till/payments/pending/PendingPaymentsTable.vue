<template>
    <table-card
        :borderless="borderless"
        :fields="fields"
        :fixed="fixed"
        :hover="hover"
        :items="items"
        :responsive="responsive"
        :select-mode="selectMode"
        :selectable="selectable"
        :sticky-header="tableHeight"
        :striped="striped"
        :tableBusy="isBusy"
        :customCells = cells
        class="shadow"
        excelFileName="История платежей"
        excelSheetName="Все платежи"
        primaryKey="id"
        responsive>
        <template #header>
            <div class="row align-items-baseline">
                <div class="col-12 col-md-6 mb-3 mb-md-0">
                    <div class=" mr-auto">История платежей</div>
                    <div class="ml-md-auto">
                        {{comment}}
                    </div>
                </div>

                <div class="row col-12  col-md-6">
                    <div class="ml-auto" v-if="branches">
                        <b-select v-model="selectedType">
                            <option :value="null">Все типы</option>
                            <option value="in">Доход</option>
                            <option value="out">Расход</option>
                        </b-select>
                    </div>
                    <div class="ml-3" v-if="branches">
                        <b-select v-model="selectedBranch">
                            <option :value="null">Все филиалы</option>
                            <option :key="branch.id" :value="branch" v-for="branch in branches">{{branch.name}}
                            </option>
                        </b-select>
                    </div>
                </div>

            </div>
        </template>

        <template slot="buttons" slot-scope="{item}">
            <div class="row">
                <a class="btn" :href="getEditUrl(item)">
                    <img alt="delete car" class="icon-btn-sm" src="/svg/edit.svg">
                </a>
                <a @click.prevent="cancelPayment(item)" class="btn" href="#">
                    <img alt="delete car" class="icon-btn-sm" src="/svg/delete.svg">
                </a>
            </div>

        </template>

        <template #footer>
            <div class="card-footer">
                <main-paginator :flowable="flowable" :onPageChange="getItems"
                                :pagination="pagination"></main-paginator>
            </div>
        </template>
    </table-card>
</template>

<script>
    import TableCardProps from '../../../common/TableCardProps.vue'

    export default {
        name: "PendingPaymentsTable",
        mixins: [TableCardProps],
        mounted() {
            if (this.payments)
                this.items = this.payments;
            this.getItems();
        },
        props: {
            comment: {
                type: String
            },
            branches: {
                type: Array,
                required: false
            },
            payments: {
                type: Array,
                required: false
            },
            flowable: {
                type: Boolean,
                default: false
            },
            type: {
                type: String,
                default: 'in'
            },
        },
        data() {
            return {
                pagination: {
                    last_page: null,
                    current_page: null
                },
                items: [],
                isBusy: false,
                cells: ['buttons'],
                selectedBranch: null,
                selectedType: null,
                fields: {
                    created_at: {
                        label: 'Дата',
                        sortable: true
                    },
                    'accountTo.description': {
                        label: 'Cчет зачисления',
                        sortable: true
                    },
                    'payer.name': {
                        label: 'Плательщик',
                        sortable: true
                    },
                    amount: {
                        label: 'Сумма',
                        sortable: true
                    },
                    'currency.isoName': {
                        label: 'Валюта',
                        sortable: true
                    },
                    'paymentItem.title': {
                        label: 'Статья',
                        sortable: true
                    },
                    'buttons': {
                        label: ''
                    },
                },
            }
        },
        methods: {
            prepareUrl() {
                let action = '/pending-payments/filtered?';

                if (this.selectedBranch)
                    action += `branch=${this.selectedBranch.id}&`;
                if (this.selectedType)
                    action += 'type=' + this.selectedType + '&';

                return action;
            },
            getItems(page = 1) {
                if (this.trips)
                    return;

                this.isBusy = true;

                let action = this.prepareUrl() + 'paginate=15&page=' + page;

                axios.get(action)
                    .then(response => {
                        this.pagination = response.data;
                        if (this.flowablePagination)
                            response.data.data.forEach(item => {
                                this.items.push(item);
                            });
                        else
                            this.items = response.data.data;
                    })
                    .catch(error => {
                        this.$root.showErrorMsg(
                            "Ошибка загрузки",
                            'Не удалось загрузить платежи. Обновите страницу'
                        )
                    })
                    .finally(
                        this.$nextTick(() => {
                            this.isBusy = false;
                        })
                    );
            },
            cancelPayment(item){
                console.log(item)
            },
            getEditUrl(item){
                if(item.paymentItem.type === 'in')
                    return '/incoming-payments/' + item.id + '/edit';
                if(item.paymentItem.type === 'out')
                    return '/outgoing-payments/' + item.id + '/edit';
            }
        },
        watch: {
            selectedBranch() {
                this.getItems();
            },
            selectedType() {
                this.getItems();
            },
        },
        components: {
            'MainPaginator': require('../../../common/MainPaginator.vue').default,
            'TableCard': require('../../../common/TableCard.vue').default
        }
    }
</script>

<style scoped>

</style>
