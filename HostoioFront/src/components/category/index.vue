<template>
    <div class="bg-white m-1 p-2">

        <div class="row">

            <div class="col-md-12">
                <div class="row">
                    <div class="col-6">
                        <h2>Category List</h2>
                    </div>
                    <div class="col-6">
                        <button  class="btn btn-sm btn-success m-2" data-toggle="modal" data-target="#addCategoryModal">
                    Add New Category
                </button>
                    </div>
                </div>

                    <table class="table table-dark">
                    <thead>
                        <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Category Name</th>
                        <th scope="col">desc</th>
                        <th scope="col">Image</th>
                        <th scope="col">Option</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="Category in result" v-bind:key="Category.id">

                            <td>{{ Category.id }}</td>
                            <td>{{ Category.name.ar }} / {{ Category.name.en }}</td>
                            <td>{{ Category.description }}</td>
                            <td>{{ Category.image }}</td>
                            <td>
                                <button type="button" class="btn btn-warning m-1" @click="edit(Category)">Edit</button>
                                <button type="button" class="btn btn-danger "  @click="remove(Category)">Delete</button>
                            </td>
                            </tr>

                    </tbody>
                    </table>
            </div>
        </div>
        <div class="modal fade" id="addCategoryModal" tabindex="-1" role="dialog" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addCategoryModalLabel">Add New Category</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <!-- Form to Add New Category -->
                        <form @submit.prevent="save">
                            <div class="form-group">
                                <label for="name_ar">Category Name (Arabic)</label>
                                <input type="text" class="form-control" v-model="category.name_ar" required>
                            </div>
                            <div class="form-group">
                                <label for="name_en">Category Name (English)</label>
                                <input type="text" class="form-control" v-model="category.name_en" required>
                            </div>
                            <div class="form-group">
                                <label for="desc">Description</label>
                                <textarea class="form-control" v-model="category.desc"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="parent_id">Parent Category</label>
                                <input type="text" class="form-control" v-model="category.parent_id">
                            </div>
                            <button type="submit" class="btn btn-primary">Save</button>
                        </form>
                    </div>
                </div>
            </div>

    </div>
    </div>
</template>
<script>
import axios from 'axios';

export default {
    name: 'Category',
    data () {
      return {
        result: {},
        category:{
            name_ar: '',
            name_en: '',
            desc: '',
            parent_id: ''
        },

      }
    },
    created() {
        this.CategoryLoad();
    },
    mounted() {
        console.log("mounted() called.......");
    },

    methods: {
        CategoryLoad()
        {
                var link = "http://127.0.0.1:8000/api/categories";
                axios.get(link)
                .then(
                    ({data})=>{
                        console.log(data);
                        this.result = data;
                    }
                )
                .catch((err)=>{
                    console.log('err',err);
                })
        },

        save()
        {
            if(this.Category.id == '')
            {
                this.saveData();
            }
            else
            {
                this.updateData();
            }

        },
        saveData()
        {
            axios.post("http://127.0.0.1:8000/api/Category", this.Category)
            .then(
            ({data})=>{
                alert("saved");
                this.CategoryLoad();
                this.Category.name = '';
                this.Category.address = '',
                this.Category.phone = ''
                this.id = ''
            }
            )

        },
        edit(Category)
        {
            this.Category = Category;
        },
        updateData()
        {
            var editrecords = 'http://127.0.0.1:8000/api/Category/'+ this.Category.id;
            axios.put(editrecords, this.Category)
            .then(
                ({data})=>{
                this.Category.name = '';
                this.Category.address = '',
                this.Category.phone = ''
                this.id = ''
                alert("Updated!!!");
                this.CategoryLoad();
                }
            );

        },

        remove(Category){
              var url = `http://127.0.0.1:8000/api/Category/${Category.id}`;
              // var url = 'http://127.0.0.1:8000/api/Category/'+ Category.id;
              axios.delete(url);
              alert("Deleted");
              this.CategoryLoad();
        }
      }
  }
  </script>
