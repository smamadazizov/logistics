<template>
    <div class="card">
        <div class="card-header">
            <div class="row align-items-baseline">
                <div class="col-10 col-md-10">
                    <slot name="header">

                    </slot>
                </div>
                <div class="col-2 col-md-2">
                    <div class="form-row align-items-center" :class="{'text-right': !selectable}">
                        <div class="col-12" v-if="excelColumns"
                             :class="{'col-md-6':selectable}">
                            <vue-excel-xlsx
                                :columns="excelColumns"
                                :data="excelData"
                                :filename="excelFileName"
                                :sheetname="excelSheetName"
                                class="btn p-0 p-md-1">
                                <img class="icon-btn-md" src="/svg/excel.svg">
                            </vue-excel-xlsx>
                        </div>
                        <div class="col-12 col-md-6" v-if="selectable">
                            <input type="checkbox"
                                   class="form-control"
                                   style="width:24px"
                                   v-b-tooltip.hover title="Выбрать все"
                                   v-model="selectAll">
                        </div>
                    </div>
                </div>

            </div>
        </div>


        <b-table :borderless="borderless"
                 :busy="isBusy"
                 :fields="fields"
                 :fixed="fixed"
                 :items="items"
                 :primary-key="primaryKey"
                 :responsive="responsive"
                 :select-mode="selectMode"
                 :selectable="selectable"
                 :striped="striped"
                 :hover="hover"
                 :tbody-tr-class="setRowClass? setRowClass:rowClass"
                 small
                 @row-clicked="onRowClick">

            <template v-slot:table-busy>
                <div class="text-center text-info my-2">
                    <b-spinner class="align-middle"></b-spinner>
                </div>
            </template>

            <template v-slot:cell(index)="data">
                <strong>{{data.index + 1}}</strong>
            </template>

            <template v-slot:cell(selected)="data">
                <span :class="checkedClass" v-if="isSelected(data.item)">
                    <slot name="selectedCell">&check;</slot>
                </span>
                <span v-else>
                    <slot name="notSelectedCell"></slot>
                </span>
            </template>

            <template v-slot:cell(created_at)="{item}">
                <span v-if="item.created_at"> {{item.created_at | luxon}} </span>
            </template>

            <template v-slot:cell(updated_at)="{item}">
                <span v-if="item.updated_at"> {{item.updated_at | luxon}} </span>
            </template>

            <template v-for="(_, key) of $scopedSlots" v-slot:[key]="data">
                <slot :name="key" v-bind="data"/>
            </template>
        </b-table>

        <slot name="footer">

        </slot>
    </div>
</template>

<script>
    import ExcelDataPreparatory from './ExcelDataPreparatory.vue'
    import TableCardProps from './TableCardProps.vue'

    export default {
        name: "TableCard",
        mixins: [ExcelDataPreparatory, TableCardProps],
        mounted() {
            this.fields.unshift({
                key:'index',
                label:'№'
            })
        },
        props: {
            items: {
                type: Array,
                required: true
            },
            fields: {
                type: Array,
                required: true
            },
            primaryKey: {
                type: String,
                required: true,
                default: 'id'
            },
            isBusy: {
                type: Boolean,
                default: false
            },
            customCells: {
                type: Array,
                default: () => []
            },
            setRowClass: {
                type: Function
            }
        },
        methods: {
            onRowClick(item) {
                if (!this.selectable)
                    return;
                if (this.isSelected(item)) {
                    this.selected = this.selected.filter(function (stored) {
                        return stored.id !== item.id
                    })
                } else {
                    this.selected.push(item)
                }

                return this.$emit('itemsSelected', this.selected);
            },
            isSelected(item) {
                return this.selected.find(function (selected) {
                    return selected.id === item.id;
                });
            },
            rowClass(item, type) {
                if (!item) return;
                if (this.isSelected(item)) return this.selectedRowClass
            }
        },
        watch: {
            selectAll() {
                if (this.selectAll) {
                    this.selected = this.items;
                } else this.selected = [];

                return this.$emit('itemsSelected', this.selected);
            }
        },
        data() {
            return {
                selected: [],
                selectAll: false
            }
        },
    }
</script>
