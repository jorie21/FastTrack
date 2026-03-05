<template>
    <!-- Fixed animated background: grid lines + floating color orbs -->
    <div aria-hidden="true">
        <!-- Grid -->
        <div class="fixed inset-0 z-0 pointer-events-none"
            style="background-image: linear-gradient(rgba(99,212,120,0.04) 1px, transparent 1px), linear-gradient(90deg, rgba(99,212,120,0.04) 1px, transparent 1px); background-size: 48px 48px;"
        />

        <!-- Orb 1 — green, top right -->
        <div class="fixed z-0 pointer-events-none rounded-full animate-float-slow"
            style="width:420px; height:420px; top:-100px; right:-80px; background: radial-gradient(circle, rgba(99,212,120,0.18), transparent 70%); filter: blur(80px);"
        />

        <!-- Orb 2 — blue, bottom left -->
        <div class="fixed z-0 pointer-events-none rounded-full animate-float-slow"
            style="width:320px; height:320px; bottom:80px; left:-80px; background: radial-gradient(circle, rgba(56,139,253,0.14), transparent 70%); filter: blur(80px); animation-delay: 3s;"
        />

        <!-- Orb 3 — amber, center -->
        <div class="fixed z-0 pointer-events-none rounded-full animate-float-slow"
            style="width:200px; height:200px; top:50%; left:55%; background: radial-gradient(circle, rgba(255,183,77,0.12), transparent 70%); filter: blur(80px); animation-delay: 5s;"
        />
    </div>
</template>