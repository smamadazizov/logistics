`
<template>
    <div>
        <table-card
            :fields="fields"
            :isBusy="isBusy"
            :items="items"
            :striped="true"
            class="shadow"
            excelFileName="История тарифных планов"
            hover
            primary-key="id"
            responsive>
            <template #header>
                История тарифных планов
            </template>

            <template v-slot:cell(created_at)="{item}">
                <span> {{item.created_at | luxon:format('dd-MM-yyyy')}} </span>
            </template>

            <template v-slot:cell(edit)="{item}">
                <a :id="'up' + item.id" href="#" @click.prevent="updateOrdersPrices(item)">
                    <img class="icon-btn-sm" src="/svg/refresh.svg" alt="">
                </a>
                <b-tooltip :target="'up' + item.id" triggers="hover">
                    Обновить стоимость заказов рассчитаных с данным тарифным планом
                </b-tooltip>
                <a :id="'e' + item.id" :href="getEditUrl(item)">
                    <img class="icon-btn-sm" src="/svg/edit.svg" alt="">
                </a>
                <b-tooltip :target="'e' + item.id" triggers="hover">
                    Редактировать тарифный план
                </b-tooltip>
                <a :id="'d' + item.id" @click="deletePrice(item)" href="#">
                    <img class="icon-btn-sm" src="/svg/delete.svg" alt="">
                </a>
            </template>

            <template #footer>
                <div class="card-footer">
                    <main-paginator :flowable="flowable"
                                    :onPageChange="getHistories"
                                    :pagination="pagination"></main-paginator>
                </div>
            </template>
        </table-card>

        <b-toast id="my-toast" solid>
            <template v-slot:toast-title>
                <div class="d-flex flex-grow-1 align-items-baseline">

                    <strong class="mr-auto" v-if="updatedOrders.length === 1">Обновлен 1 заказ</strong>
                    <strong class="mr-auto" v-else-if="updatedOrders.length > 1 && updatedOrders.length < 5">
                        Обновлено {{updatedOrders.length}} заказа
                    </strong>
                    <strong class="mr-auto" v-else>Обновлено {{updatedOrders.length}} заказов</strong>
                </div>
            </template>

            <div v-if="updatedOrders.length === 0">
                По данному тарифному плану нет расчитанных заказов
            </div>
            <div v-else>
                <ol>
                    <li v-for="order in updatedOrders" :key="order.id">
                        <a :href="'/orders/' + order.id">Заказ от {{order.created_at.split(' ')[0]}} | Цена:
                            {{order.totalPrice}} $</a>
                    </li>
                </ol>


            </div>

        </b-toast>
    </div>
</template>

<script>
    import {hideBusySpinner, showBusySpinner} from "../../tools";

    export default {
        name: "TariffHistoriesViewer",
        mounted() {
            this.getHistories();
        },
        props: {
            flowable: {
                type: Boolean,
                required: false,
                default: false
            }
        },
        data() {
            return {
                pagination: {
                    last_page: null,
                    current_page: null
                },
                items: [],
                isBusy: false,
                updatedOrders: [],
                fields: [
                    {
                        key: 'created_at',
                        label: 'Дата',
                        sortable: true
                    },
                    {
                        key: 'tariff.name',
                        label: 'Тариф',
                        sortable: true
                    },
                    {
                        key: 'lowerLimit',
                        label: 'Нижний предел',
                        sortable: true
                    },
                    {
                        key: 'mediumLimit',
                        label: 'Средний предел',
                        sortable: true
                    },
                    {
                        key: 'upperLimit',
                        label: 'Верхний предел',
                        sortable: true
                    },
                    {
                        key: 'pricePerCube',
                        label: 'Цена за куб',
                        sortable: true
                    },
                    {
                        key: 'discountForLowerLimit',
                        label: 'Скидка НП',
                        sortable: true
                    },
                    {
                        key: 'discountForMediumLimit',
                        label: 'Скидка СП',
                        sortable: true
                    },
                    {
                        key: 'agreedPricePerKg',
                        label: 'Договорная',
                        sortable: true
                    },
                    {
                        key: 'maxWeightPerCube',
                        label: 'Макс. вес на куб',
                        sortable: true
                    },
                    {
                        key: 'pricePerExtraKg',
                        label: 'Цена за доп. кг',
                        sortable: true
                    },
                    {
                        key: 'maxWeight',
                        label: 'Общий вес',
                        sortable: true
                    },
                    {
                        key: 'maxCubage',
                        label: 'Общая кубатура',
                        sortable: true
                    },
                    {
                        key: 'totalMoney',
                        label: 'Сумма',
                        sortable: true
                    },
                    {
                        key: 'edit',
                        label: ''
                    }
                ]
            }
        },
        methods: {
            getEditUrl(item) {
                return '/tariff-price-histories/' + item.id + '/edit';
            },
            deletePrice(item) {
                this.$bvModal.msgBoxConfirm('Вы действительно хотите удалить расценку тарифа '
                    + item.tariff.name + ' от ' + item.created_at + '?', {
                    okTitle: 'Удалить',
                    okVariant: 'danger',
                    cancelTitle: 'Отмена',
                    centered: true
                })
                    .then(confirm => {
                        if (confirm) {
                            showBusySpinner();
                            axios.delete('/tariff-price-histories/' + item.id)
                                .then(_ => {
                                    this.$bvToast.toast('Расценка тарифа '
                                        + item.tariff.name + ' от ' + item.created_at
                                        + ' удалена', {
                                        title: 'Успешно удалено',
                                        autoHideDelay: 5000,
                                        appendToast: true,
                                        variant: 'success'
                                    });

                                    this.items = this.items.filter(i => i.id !== item.id)
                                })
                                .catch(e => {
                                    let message = 'Не удалось удалить расценку';

                                    if (e.response.status === 422)
                                        message = e.response.data.message

                                    this.$bvToast.toast(message, {
                                        title: 'Ошибка удаления',
                                        autoHideDelay: 5000,
                                        appendToast: true,
                                        variant: 'danger'
                                    });
                                })
                                .then(_ => this.$nextTick(_ => hideBusySpinner()))
                        }
                    })
                    .catch(err => {
                        // An error occurred
                    })
            },
            async updateOrdersPrices(history) {
                tShowSpinner();
                try {
                    const response = await axios.post(`/tariff-price-history/${history.id}/orders/update-price`);
                    this.updatedOrders = response.data;
                } catch (e) {

                }
                tHideSpinner();
                this.showNotification();
            },
            showNotification(orders) {
                this.$bvToast.show('my-toast')
            },
            async getHistories(page = 1) {
                this.isBusy = true;

                let action = '/tariff-price-histories/all?paginate=10&page=' + page;

                try {
                    const response = await axios.get(action);
                    this.pagination = response.data;
                    if (this.flowablePagination)
                        response.data.data.forEach(item => {
                            this.items.push(item);
                        });
                    else
                        this.items = response.data.data;
                } catch (e) {
                    this.$root.showErrorMsg(
                        'Ошибка загрузки',
                        'Не удалось загрузить историю расценок. Попробуйте обновить список позднее'
                    )
                }

                this.$nextTick(() => {
                    this.isBusy = false;
                });
            }
        },
        computed: {
            currentPage() {
                return this.pagination.current_page;
            },
            lastPage() {
                return this.pagination.last_page
            }
        },
        components: {
            'MainPaginator': require('../common/MainPaginator.vue').default,
            'TableCard': require('../common/TableCard.vue').default
        }
    }
</script>

