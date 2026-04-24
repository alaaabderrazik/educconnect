@extends('layouts.dashboard')

@section('title', 'Configuration du Site')
@section('user_role', 'Administrateur')

@section('sidebar_menu')
    @include('dashboard.admin.sidebar')
@endsection

@section('content')
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Paramètres Généraux ⚙️</h1>
            <p class="text-slate-500 text-sm mt-1">Gérez l'identité visuelle et les coordonnées de l'établissement.</p>
        </div>
    </div>

    @if(session('success'))
    <div class="mb-6 p-4 bg-emerald-50 border border-emerald-100 text-emerald-600 rounded-2xl flex items-center gap-3 animate-fade-in text-sm font-bold">
        <i class="fa-solid fa-circle-check"></i>
        {{ session('success') }}
    </div>
    @endif

    <form action="{{ route('dashboard.admin.settings.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 space-y-6">
                <!-- Identity Section -->
                <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
                    <div class="p-6 border-b border-slate-100 bg-slate-50/50">
                        <h3 class="text-sm font-black text-slate-900 uppercase tracking-widest flex items-center gap-2">
                            <i class="fa-solid fa-id-card text-blue-500 font-normal"></i> Identité du Site
                        </h3>
                    </div>
                    <div class="p-8 space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Nom de l'école</label>
                                <input type="text" name="school_name" value="{{ $settings['school_name'] ?? '' }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-4 focus:ring-blue-500/10 font-medium text-sm">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Couleur Principale</label>
                                <div class="flex gap-2">
                                    <input type="color" name="primary_color" value="{{ $settings['primary_color'] ?? '#2563eb' }}" class="h-11 w-11 p-1 bg-white border border-slate-200 rounded-xl cursor-pointer">
                                    <input type="text" readonly value="{{ $settings['primary_color'] ?? '#2563eb' }}" class="flex-grow px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl font-mono text-sm">
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Couleur Secondaire</label>
                                <div class="flex gap-2">
                                    <input type="color" name="secondary_color" value="{{ $settings['secondary_color'] ?? '#fbbf24' }}" class="h-11 w-11 p-1 bg-white border border-slate-200 rounded-xl cursor-pointer">
                                    <input type="text" readonly value="{{ $settings['secondary_color'] ?? '#fbbf24' }}" class="flex-grow px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl font-mono text-sm">
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Famille de police (Font Family)</label>
                                <input type="text" name="font_family" value="{{ $settings['font_family'] ?? 'Inter, sans-serif' }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-4 focus:ring-blue-500/10 font-medium text-sm">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact Section -->
                <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
                    <div class="p-6 border-b border-slate-100 bg-slate-50/50">
                        <h3 class="text-sm font-black text-slate-900 uppercase tracking-widest flex items-center gap-2">
                            <i class="fa-solid fa-address-book text-emerald-500 font-normal"></i> Coordonnées
                        </h3>
                    </div>
                    <div class="p-8 space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Email de Contact</label>
                                <input type="email" name="contact_email" value="{{ $settings['contact_email'] ?? '' }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-4 focus:ring-blue-500/10 font-medium text-sm">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Téléphone</label>
                                <input type="text" name="contact_phone" value="{{ $settings['contact_phone'] ?? '' }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-4 focus:ring-blue-500/10 font-medium text-sm">
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Adresse Physique</label>
                            <textarea name="contact_address" rows="2" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-4 focus:ring-blue-500/10 font-medium text-sm">{{ $settings['contact_address'] ?? '' }}</textarea>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Lien Google Maps (Embed URL)</label>
                            <input type="text" name="google_maps_link" value="{{ $settings['google_maps_link'] ?? '' }}" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-4 focus:ring-blue-500/10 font-medium text-sm">
                        </div>
                    </div>
                </div>

                <!-- Social Media -->
                <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
                    <div class="p-6 border-b border-slate-100 bg-slate-50/50">
                        <h3 class="text-sm font-black text-slate-900 uppercase tracking-widest flex items-center gap-2">
                            <i class="fa-brands fa-share-nodes text-indigo-500 font-normal"></i> Réseaux Sociaux
                        </h3>
                    </div>
                    <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                        @foreach(['facebook', 'twitter', 'linkedin', 'instagram'] as $social)
                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">{{ ucfirst($social) }} URL</label>
                            <div class="relative">
                                <i class="fa-brands fa-{{ $social }} absolute left-4 top-1/2 -translate-y-1/2 text-slate-300"></i>
                                <input type="text" name="social_{{ $social }}" value="{{ $settings['social_'.$social] ?? '' }}" class="w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-4 focus:ring-blue-500/10 font-medium text-sm">
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <div class="flex justify-end pt-4">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-10 py-4 rounded-2xl font-black text-sm transition-all shadow-xl shadow-blue-500/20 flex items-center gap-3">
                        <i class="fa-solid fa-cloud-arrow-up"></i> Mettre à jour la configuration
                    </button>
                </div>
            </div>

            <!-- Sidebar Info -->
            <div class="space-y-6">
                <!-- Logo -->
                <div class="bg-slate-900 rounded-3xl p-8 text-white shadow-2xl">
                    <h4 class="text-xl font-black mb-4">Logo du Site</h4>
                    <div class="aspect-video bg-white/5 rounded-2xl border border-white/10 flex items-center justify-center mb-6 overflow-hidden relative">
                        @if(!empty($settings['site_logo']))
                        <img src="{{ asset('storage/' . $settings['site_logo']) }}" alt="Logo" class="max-w-[70%] max-h-[70%] object-contain">
                        @else
                        <div class="text-white/30 font-bold">Aucun logo</div>
                        @endif
                    </div>
                    <div class="space-y-4">
                        <p class="text-slate-400 text-sm leading-relaxed">
                            Le logo est utilisé dans le header public, le footer, et sur l'interface de connexion.
                        </p>
                        <label class="block w-full text-center bg-white/10 hover:bg-white/20 px-4 py-3 rounded-xl border border-white/10 cursor-pointer transition-all text-sm font-bold">
                            Choisir un Logo
                            <input type="file" name="site_logo" accept="image/*" class="hidden" onchange="this.parentElement.querySelector('span').textContent = this.files[0].name">
                            <span class="block text-xs font-normal opacity-70 mt-1">Format PNG recommandé</span>
                        </label>
                    </div>
                </div>
                
                <!-- Favicon -->
                <div class="bg-white border border-slate-100 rounded-3xl p-8 shadow-sm">
                    <h4 class="text-xl font-black mb-4 text-slate-800">Favicon</h4>
                    <div class="h-16 w-16 bg-slate-50 rounded-xl border border-slate-200 flex items-center justify-center mb-6 overflow-hidden">
                        @if(!empty($settings['site_favicon']))
                        <img src="{{ asset('storage/' . $settings['site_favicon']) }}" alt="Favicon" class="w-8 h-8 object-contain">
                        @else
                        <i class="fa-solid fa-globe text-slate-300"></i>
                        @endif
                    </div>
                    <div class="space-y-4">
                        <label class="block w-full text-center bg-slate-100 hover:bg-slate-200 text-slate-700 px-4 py-3 rounded-xl cursor-pointer transition-all text-sm font-bold">
                            Choisir un Favicon
                            <input type="file" name="site_favicon" accept="image/x-icon,image/png" class="hidden" onchange="this.parentElement.querySelector('span').textContent = this.files[0].name">
                            <span class="block text-xs font-normal opacity-70 mt-1">Format .ico ou .png 32x32</span>
                        </label>
                    </div>
                </div>

                <div class="bg-blue-50 rounded-3xl p-8 border border-blue-100">
                    <div class="w-12 h-12 bg-blue-600 text-white rounded-xl flex items-center justify-center text-xl mb-4">
                        <i class="fa-solid fa-lightbulb"></i>
                    </div>
                    <h4 class="text-lg font-bold text-slate-900 mb-2">Conseil</h4>
                    <p class="text-slate-600 text-sm leading-relaxed">
                        Les modifications effectuées ici impactent l'ensemble du site public et de l'interface dashboard. Assurez-vous que les coordonnées sont à jour.
                    </p>
                </div>
            </div>
        </div>
    </form>
@endsection
