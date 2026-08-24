@extends('layouts.app')

@section('title', 'Support Agent Login - Online Support System')

@section('content')
<div class="max-w-md mx-auto py-8">
    <div class="glass-card rounded-2xl p-6 sm:p-8 space-y-6 shadow-2xl border border-slate-700/50">
        
        <!-- Header -->
        <div class="text-center space-y-2">
            <h2 class="text-2xl font-extrabold text-blue-600">Support Agent Login</h2>
            <p class="text-xs text-slate-400">Sign in to manage pending tickets and respond to customer queries.</p>
        </div>

        <!-- Demo Credentials Chip -->
        <div class="p-3.5 rounded-xl bg-blue-100 border border-blue-400 text-xs space-y-1 text-slate-600">
            <div class="flex items-center justify-between font-semibold text-blue-400">
                <span>Demo Agent Credentials:</span>
                <button type="button" onclick="fillDemoCredentials()" class="underline text-[11px] text-blue-300 hover:text-white">Auto Fill</button>
            </div>
            <div>Email: <code class="text-slate-800">agent@support.com</code></div>
            <div>Password: <code class="text-slate-800">password123</code></div>
        </div>

        <!-- Login Form -->
        <form method="POST" action="{{ route('agent.login.post') }}" class="space-y-4">
            @csrf
            
            <!-- Email -->
            <div>
                <label for="email" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Email Address</label>
                <input type="email" id="email" name="email" value="{{ old('email', 'agent@support.com') }}" required 
                       class="w-full px-4 py-3 rounded-xl glass-input text-sm focus:outline-none text-black">
                @error('email')
                    <p class="text-xs text-rose-400 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Password</label>
                <input type="password" id="password" name="password" value="password123" required 
                       class="w-full px-4 py-3  text-black rounded-xl glass-input text-sm focus:outline-none">
                @error('password')
                    <p class="text-xs text-rose-400 mt-1">{{ $message }}</p>
                @enderror
            </div>


            <!-- Submit Button -->
            <button type="submit" class="w-full py-3.5 rounded-xl bg-blue-600 hover:bg-blue-500 text-white font-semibold text-sm shadow-xl shadow-blue-600/30 transition-all">
                Sign In to Agent Portal
            </button>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function fillDemoCredentials() {
        document.getElementById('email').value = 'agent@support.com';
        document.getElementById('password').value = 'password123';
    }
</script>
@endpush
