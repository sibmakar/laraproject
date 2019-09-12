<template>
    <div class="flex items-center mr-8">
        <button class="w-4 h-4 rounded-full bg-default border mr-2 focus:outline-none"
                :class="{ 'border-blue-500': selectedTheme == 'theme-light' }"
                @click="selectedTheme = 'theme-light'"
                style="background: #fff"></button>
        <button class="w-4 h-4 rounded-full bg-default border mr-2 focus:outline-none"
                :class="{ 'border-blue-500': selectedTheme == 'theme-dark' }"
                @click="selectedTheme = 'theme-dark'"
                style="background: #222"></button>
    </div>
</template>

<script>
    export default {
        name: "ThemeSwitcher",
        mounted() {
            console.log('Component mounted.')
        },
        data() {
            return {
              selectedTheme: 'theme-light'
            };
        },

        created() {
          this.selectedTheme = localStorage.getItem('theme') || 'theme-light';
        },

        watch: {
            selectedTheme(){
                //this.$emit('changed', this.selectedTheme);
                document.body.className = document.body.className.replace(/theme-\w+/, this.selectedTheme);
                localStorage.setItem('theme', this.selectedTheme);
            }
        }
    }
</script>

