<template>
 <div>
     <nav class="navbar navbar-expand-lg bg-light">
             <div class="container" >
                 <div class="navbar-nav">
                     <router-link class="nav-link" :to="{name: 'home'}">Главная</router-link>
                     <router-link class="nav-link" :to="{name: 'desks'}">Доски</router-link>
                 </div>
             </div>
     </nav>
     <div>
         <router-view></router-view>
     </div>
 </div>
</template>

<script>
export default {

}
</script>

