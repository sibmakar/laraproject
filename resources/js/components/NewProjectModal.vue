<template>
    <modal name="new-project" classes="p-10 bg-card rounded-lg" height="auto">


        <h1 class="text-center mb-16 text-2xl">Let's Start Something New!</h1>

        <form @submit.prevent="submit">

            <div class="flex">

                <div class="flex-1 mr-4">

                    <div class="mr-4 mb-2">
                        <label for="title" class="text-sm block mb-1">Title</label>
                        <input type="text" id="title"
                               class="border border-default p-2 text-sm block w-full rounded"
                               :class="form.errors.title ? 'border-error' : 'border-dafault'"
                               v-model="form.title">
                        <span class="text-xs font-italic text-error" v-if="form.errors.title"
                              v-text="form.errors.title[0]"></span>
                    </div>

                    <div class="mr-4">
                        <label for="description" class="text-sm block mb-1">Description</label>
                        <textarea id="description"
                                  class="border border-default p-2 text-sm block w-full rounded"
                                  rows="7"
                                  :class="form.errors.description ? 'border-error' : 'border-default'"
                                  v-model="form.description"></textarea>
                        <span class="text-xs font-italic text-error" v-if="form.errors.description"
                              v-text="form.errors.description[0]"></span>
                    </div>


                </div>
                <div class="flex-1 ml-4">

                    <div class="mr-4  mb-2">
                        <label class="text-sm block mb-1">Let's add some tasks?</label>
                        <input type="text"
                               placeholder="Task 1"
                               class="border border-default p-2 text-sm block w-full rounded mb-2"
                               v-for="task in form.tasks"
                               v-model="task.body">
                    </div>

                    <button type="button" class="inline-flex items-center text-xs" @click="addTask">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"
                             class="text-default">
                            <g fill="none" fill-rule="evenodd" opacity=".307">
                                <g class="fill-current">
                                    <path class="heroicon-ui"
                                          d="M12 22a10 10 0 1 1 0-20 10 10 0 0 1 0 20zm0-2a8 8 0 1 0 0-16 8 8 0 0 0 0 16zm1-9h2a1 1 0 0 1 0 2h-2v2a1 1 0 0 1-2 0v-2H9a1 1 0 0 1 0-2h2V9a1 1 0 0 1 2 0v2z"/>
                                </g>
                            </g>
                        </svg>
                        <span class="ml-2">Add New Task Field</span>
                    </button>
                </div>

            </div>

            <footer class="flex justify-end">
                <button type="button" class="button mr-2 is-outlined" @click="$modal.hide('new-project')">Cancel</button>
                <button class="button">Create Project</button>

            </footer>
        </form>


    </modal>
</template>

<script>
    import BirdBoardForm from './BirdBoardForm';

    export default {
        data() {
            return {
                form: new BirdBoardForm ({
                    title: '',
                    description: '',
                    tasks: [
                        {body: ''},
                    ]
                })
            }
        },
        methods: {
            addTask() {
                this.form.tasks.push({body: ''});
            },

            async submit(){
                this.form.submit('/projects')
                    .then(response => location = response.data.message)
                    .catch(error => alert('error'));
            }
        }
    }
</script>
