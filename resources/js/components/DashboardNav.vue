<template>
    <div class="list-group list-group-flush">
        <button class="list-group-item list-group-item-action" @click="viewMyProfile()">My Profile</button>
        <button class="list-group-item list-group-item-action" @click="viewEditProfile()">Edit Profile</button>
        <button class="list-group-item list-group-item-action" @click="viewChangePassword()">Change Password</button>
        <button class="list-group-item list-group-item-action" @click="viewMyPosts()">My Posts</button>
    </div>
</template>

<script>
    export default {
        methods: {
            viewMyProfile: function(){
                
            }
        },
    }
</script>
