@extends('layouts.dashboard')
@section('title', 'Mon Profil')
@section('user_role', 'Professeur')
@section('sidebar_menu')@include('dashboard.professor.sidebar')@endsection

@section('content')
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Mon Profil 👤</h1>
        <p class="text-slate-500 text-sm mt-1">Consultez et modifiez vos informations personnelles.</p>
    </div>

    @if(session('success'))
    <div class="mb-6 p-4 bg-emerald-50 border border-emerald-100 text-emerald-600 rounded-2xl flex items-center gap-3">
        <i class="fa-solid fa-circle-check"></i><span class="text-sm font-bold">{{ session('success') }}</span>
    </div>
    @endif

    @if($errors->any())
    <div class="mb-6 p-4 bg-rose-50 border border-rose-100 text-rose-600 rounded-2xl">
        <ul class="list-disc pl-4 text-sm space-y-1">
            @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('dashboard.professor.profile.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            {{-- Profile Photo Card --}}
            <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-8 flex flex-col items-center text-center">
                <div class="relative mb-5">
                    @if($prof->profile_photo)
                    <img src="{{ asset('storage/' . $prof->profile_photo) }}" class="w-28 h-28 rounded-2xl object-cover shadow-md">
                    @else
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($prof->name) }}&size=112&background=3b82f6&color=fff" class="w-28 h-28 rounded-2xl shadow-md">
                    @endif
                    <label for="photo" class="absolute -bottom-2 -right-2 w-9 h-9 bg-blue-600 text-white rounded-xl flex items-center justify-center cursor-pointer hover:bg-blue-700 transition-all shadow-lg">
                        <i class="fa-solid fa-camera text-sm"></i>
                        <input type="file" id="photo" name="profile_photo" accept="image/*" class="hidden" onchange="previewPhoto(this)">
                    </label>
                </div>
                <h3 class="font-black text-xl text-slate-900">{{ $prof->first_name }} {{ $prof->last_name }}</h3>
                <p class="text-blue-600 font-bold text-sm mt-1">{{ $prof->professorDetails->specialty ?? 'Professeur' }}</p>
                <p class="text-slate-400 text-xs mt-0.5">{{ $prof->professorDetails->subject->name ?? 'N/A' }}</p>
                <div class="mt-6 w-full space-y-2 text-left">
                    <div class="flex items-center gap-2 text-xs text-slate-600">
                        <i class="fa-solid fa-envelope text-blue-400 w-4 text-center"></i>
                        <span class="font-medium truncate">{{ $prof->email }}</span>
                    </div>
                    @if($prof->phone)
                    <div class="flex items-center gap-2 text-xs text-slate-600">
                        <i class="fa-solid fa-phone text-emerald-400 w-4 text-center"></i>
                        <span class="font-medium">{{ $prof->phone }}</span>
                    </div>
                    @endif
                    @if($prof->city)
                    <div class="flex items-center gap-2 text-xs text-slate-600">
                        <i class="fa-solid fa-location-dot text-rose-400 w-4 text-center"></i>
                        <span class="font-medium">{{ $prof->city }}</span>
                    </div>
                    @endif
                    <div class="flex items-center gap-2 text-xs text-slate-600">
                        <i class="fa-solid fa-id-badge text-amber-400 w-4 text-center"></i>
                        <span class="font-medium">{{ $prof->professorDetails->professor_code ?? 'N/A' }}</span>
                    </div>
                </div>
            </div>

            {{-- Edit Form --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Personal Info --}}
                <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-8">
                    <h3 class="font-black text-slate-900 mb-6 flex items-center gap-2">
                        <i class="fa-solid fa-user text-blue-500"></i> Informations Personnelles
                    </h3>
                    <div class="grid grid-cols-2 gap-5">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Prénom</label>
                            <input type="text" name="first_name" value="{{ old('first_name', $prof->first_name) }}" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-4 focus:ring-blue-500/10 font-bold text-sm">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Nom</label>
                            <input type="text" name="last_name" value="{{ old('last_name', $prof->last_name) }}" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-4 focus:ring-blue-500/10 font-bold text-sm">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Email</label>
                            <input type="email" name="email" value="{{ old('email', $prof->email) }}" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-4 focus:ring-blue-500/10 font-bold text-sm">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Téléphone</label>
                            <input type="tel" name="phone" value="{{ old('phone', $prof->phone) }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-4 focus:ring-blue-500/10 font-bold text-sm">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Ville</label>
                            <input type="text" name="city" value="{{ old('city', $prof->city) }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-4 focus:ring-blue-500/10 font-bold text-sm">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Adresse</label>
                            <input type="text" name="address" value="{{ old('address', $prof->address) }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-4 focus:ring-blue-500/10 font-bold text-sm">
                        </div>
                    </div>
                </div>

                {{-- Password Change --}}
                <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-8">
                    <h3 class="font-black text-slate-900 mb-6 flex items-center gap-2">
                        <i class="fa-solid fa-lock text-amber-500"></i> Changer le Mot de Passe
                    </h3>
                    <div class="grid grid-cols-2 gap-5">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Nouveau mot de passe</label>
                            <input type="password" name="password" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-4 focus:ring-amber-500/10 font-bold text-sm" placeholder="Laisser vide pour ne pas changer">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Confirmer le mot de passe</label>
                            <input type="password" name="password_confirmation" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-4 focus:ring-amber-500/10 font-bold text-sm" placeholder="Répéter le mot de passe">
                        </div>
                    </div>
                </div>

                <button type="submit" class="w-full py-4 bg-blue-600 text-white rounded-2xl font-black text-sm hover:bg-blue-700 transition-all shadow-xl shadow-blue-500/20">
                    <i class="fa-solid fa-floppy-disk mr-2"></i>Enregistrer les modifications
                </button>
            </div>
        </div>
    </form>

    <script>
        function previewPhoto(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = e => document.querySelector('.w-28.h-28').src = e.target.result;
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
@endsection
