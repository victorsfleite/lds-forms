<template lang="pug">
    span.position-relative(v-click-outside="outsideClick", @click="$emit('click', $event)")
        button.btn.dropdown-toggle(
            data-toggle="dropdown",
            :class="buttonClass",
            @click="toggleOpen")
            span {{ selectedLabel }}

        .dropdown-menu.w-100(:class="{[dropdownClass]: true , 'd-block': opened}")
            .px-2.pb-2.border-bottom(v-if="searchable")
                input-text.mb-0(:placeholder="searchPlaceholder", :input-class="searchInputClass",
                    ref="input",
                    v-model="search",
                    icon="fa fa-search")
            div(style="max-height: 310px; overflow-y: auto")
                a.dropdown-item(
                    v-for="item of _options",
                    href="#",
                    :class="{'active': isSelected(item)}"
                    @click.prevent="select(item)")
                    span {{ item[label] }}
</template>

<script>
export default {
    props: {
        placeholder: { default: 'Choose an option' },
        searchable: { default: false },
        searchPlaceholder: { default: 'Search...' },
        value: { required: false },
        options: { default: () => [], type: Array },
        label: { default: 'name' },
        trackBy: { default: 'id' },
        dropdownClass: { default: null },
        buttonClass: { default: 'btn-default' },
        searchInputClass: { default: 'form-control-sm' },
    },

    data() {
        return {
            opened: false,
            isString: false,
            search: null,
            selected: this.value || null,
        };
    },

    computed: {
        _options() {
            this.isString = false;
            if (!this.options.length) return this.options;
            let options = [];
            if ('string' === typeof this.options[0]) {
                this.isString = true;
                options = this.options.map(option => ({ [this.trackBy]: option, [this.label]: option }));
            } else {
                options = this.options;
            }

            if (!this.search) return options;

            return options.filter(option => option[this.label].includes(this.search));
        },

        selectedLabel() {
            if (!this.selected) return this.placeholder;
            if ('string' === typeof this.selected) return this.selected;
            return this.selected[this.label];
        },
    },

    watch: {
        value() {
            this.selected = this.value;
        },
    },

    methods: {
        select(item) {
            this.selected = this.isString ? item[this.trackBy] : item;
            this.$emit('input', this.selected);
        },

        isSelected(item) {
            if (!this.selected) {
                return false;
            }

            return this.isString ? item[this.trackBy] === this.selected : item[this.trackBy] === this.selected[this.trackBy];
        },

        toggleOpen() {
            this.opened = !this.opened;
            this.noOptions = false;
            if (this.opened && this.searchable) {
                this.search = null;
                this.$nextTick(() => this.$refs.input.focus());
                return this.$emit('opened');
            }
            return this.$emit('closed');
        },

        outsideClick() {
            this.opened = false;
        },
    },
};
</script>
