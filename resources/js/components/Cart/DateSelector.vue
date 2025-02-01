<template>
    <div class="date-selector">
        <span>Select Date: </span>
        <select
            v-model="selectedDay"
            class="form-select form-select-sm mt-2"
            @change="onDateChange"
        >
            <option
                v-for="(day, index) in availableDays"
                :key="index"
                :value="day.name"
            >
                {{ day.name }} - {{ day.date }}
            </option>
        </select>
    </div>
</template>

<script>
export default {
    name: "DateSelector",
    props: {
        availableDays: {
            type: Array,
            required: true,
        },
        initialSelectedDay: {
            type: String,
            default: "",
        },
    },
    data() {
        return {
            selectedDay: this.initialSelectedDay,
        };
    },
    methods: {
        onDateChange() {
            this.$emit("date-change", this.selectedDay);
        },
    },
    watch: {
        initialSelectedDay(newVal) {
            this.selectedDay = newVal;
        },
    },
};
</script>

<style scoped>
.date-selector {
    display: flex;
    align-items: center;
    gap: 10px;
}
</style>