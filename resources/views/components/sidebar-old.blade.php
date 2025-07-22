<aside :class="sidebarToggle ? 'translate-x-0 lg:w-[90px]' : '-translate-x-full'"
    class="sidebar fixed left-0 top-0 z-50 flex h-screen w-[290px] flex-col overflow-y-hidden border-r border-gray-200 bg-white px-5 transition-all dark:border-gray-800 dark:bg-black lg:static lg:translate-x-0">

    <!-- SIDEBAR HEADER -->
    <div class="pt-8 pb-7 px-4">
        <a href="{{ homeRouteForUser() }}" class="flex items-center">
            <img src="{{ asset('images/logo-app.png') }}" alt="Logo SDN Darmorejo 02"
                class="h-9 w-10 rounded-full object-cover transition-all duration-300" />
            <span class="ml-3 text-lg font-semibold text-gray-800 dark:text-white transition-all duration-300"
                :class="sidebarToggle ? 'hidden' : 'inline-block'">
                SDN Darmorejo 02
            </span>
        </a>
    </div>
    <!-- END SIDEBAR HEADER -->

    <div class="flex flex-col overflow-y-auto duration-300 ease-linear no-scrollbar">
        <!-- Sidebar Menu -->
        <nav x-data="{ selected: $persist('Dashboard') }">
            <!-- Menu Group -->
            <div>
                <h3 class="mb-4 text-xs uppercase leading-[20px] text-gray-400">
                    <span class="menu-group-title" :class="sidebarToggle ? 'lg:hidden' : ''">
                        MAIN DATA
                    </span>

                    <svg :class="sidebarToggle ? 'lg:block hidden' : 'hidden'"
                        class="mx-auto fill-current menu-group-icon" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M5.99915 10.2451C6.96564 10.2451 7.74915 11.0286 7.74915 11.9951V12.0051C7.74915 12.9716 6.96564 13.7551 5.99915 13.7551C5.03265 13.7551 4.24915 12.9716 4.24915 12.0051V11.9951C4.24915 11.0286 5.03265 10.2451 5.99915 10.2451ZM17.9991 10.2451C18.9656 10.2451 19.7491 11.0286 19.7491 11.9951V12.0051C19.7491 12.9716 18.9656 13.7551 17.9991 13.7551C17.0326 13.7551 16.2491 12.9716 16.2491 12.0051V11.9951C16.2491 11.0286 17.0326 10.2451 17.9991 10.2451ZM13.7491 11.9951C13.7491 11.0286 12.9656 10.2451 11.9991 10.2451C11.0326 10.2451 10.2491 11.0286 10.2491 11.9951V12.0051C10.2491 12.9716 11.0326 13.7551 11.9991 13.7551C12.9656 13.7551 13.7491 12.9716 13.7491 12.0051V11.9951Z"
                            fill="" />
                    </svg>
                </h3>

                <!-- Menu Item -->
                <ul class="flex flex-col gap-4 mb-6">
                    <!-- Menu Item Dashboard-->
                    <li>
                        <a href="{{ route('dashboard') }}" class="menu-item group"
                            :class="page === 'Dashboard' ? 'menu-item-active' : 'menu-item-inactive'">
                            <svg :class="page === 'Dashboard' ? 'menu-item-icon-active' : 'menu-item-icon-inactive'"
                                width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M5.5 3.25C4.25736 3.25 3.25 4.25736 3.25 5.5V8.99998C3.25 10.2426 4.25736 11.25 5.5 11.25H9C10.2426 11.25 11.25 10.2426 11.25 8.99998V5.5C11.25 4.25736 10.2426 3.25 9 3.25H5.5ZM4.75 5.5C4.75 5.08579 5.08579 4.75 5.5 4.75H9C9.41421 4.75 9.75 5.08579 9.75 5.5V8.99998C9.75 9.41419 9.41421 9.74998 9 9.74998H5.5C5.08579 9.74998 4.75 9.41419 4.75 8.99998V5.5ZM5.5 12.75C4.25736 12.75 3.25 13.7574 3.25 15V18.5C3.25 19.7426 4.25736 20.75 5.5 20.75H9C10.2426 20.75 11.25 19.7427 11.25 18.5V15C11.25 13.7574 10.2426 12.75 9 12.75H5.5ZM4.75 15C4.75 14.5858 5.08579 14.25 5.5 14.25H9C9.41421 14.25 9.75 14.5858 9.75 15V18.5C9.75 18.9142 9.41421 19.25 9 19.25H5.5C5.08579 19.25 4.75 18.9142 4.75 18.5V15ZM12.75 5.5C12.75 4.25736 13.7574 3.25 15 3.25H18.5C19.7426 3.25 20.75 4.25736 20.75 5.5V8.99998C20.75 10.2426 19.7426 11.25 18.5 11.25H15C13.7574 11.25 12.75 10.2426 12.75 8.99998V5.5ZM15 4.75C14.5858 4.75 14.25 5.08579 14.25 5.5V8.99998C14.25 9.41419 14.5858 9.74998 15 9.74998H18.5C18.9142 9.74998 19.25 9.41419 19.25 8.99998V5.5C19.25 5.08579 18.9142 4.75 18.5 4.75H15ZM15 12.75C13.7574 12.75 12.75 13.7574 12.75 15V18.5C12.75 19.7426 13.7574 20.75 15 20.75H18.5C19.7426 20.75 20.75 19.7427 20.75 18.5V15C20.75 13.7574 19.7426 12.75 18.5 12.75H15ZM14.25 15C14.25 14.5858 14.5858 14.25 15 14.25H18.5C18.9142 14.25 19.25 14.5858 19.25 15V18.5C19.25 18.9142 18.9142 19.25 18.5 19.25H15C14.5858 19.25 14.25 18.9142 14.25 18.5V15Z"
                                    fill="" />
                            </svg>

                            <span class="menu-item-text" :class="sidebarToggle ? 'lg:hidden' : ''">
                                Dashboard
                            </span>
                        </a>
                    </li>
                    <!-- Menu Item Dashboard -->

                    <!-- Menu Item Profile -->
                    <li>
                        <a href="{{ route('profile.edit') }}" class="menu-item group"
                            :class="page === 'Profile' ? 'menu-item-active' : 'menu-item-inactive'">
                            <svg :class="page === 'Profile' ? 'menu-item-icon-active' : 'menu-item-icon-inactive'"
                                width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M12 3.5C7.30558 3.5 3.5 7.30558 3.5 12C3.5 14.1526 4.3002 16.1184 5.61936 17.616C6.17279 15.3096 8.24852 13.5955 10.7246 13.5955H13.2746C15.7509 13.5955 17.8268 15.31 18.38 17.6167C19.6996 16.119 20.5 14.153 20.5 12C20.5 7.30558 16.6944 3.5 12 3.5ZM17.0246 18.8566V18.8455C17.0246 16.7744 15.3457 15.0955 13.2746 15.0955H10.7246C8.65354 15.0955 6.97461 16.7744 6.97461 18.8455V18.856C8.38223 19.8895 10.1198 20.5 12 20.5C13.8798 20.5 15.6171 19.8898 17.0246 18.8566ZM2 12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12C22 17.5228 17.5228 22 12 22C6.47715 22 2 17.5228 2 12ZM11.9991 7.25C10.8847 7.25 9.98126 8.15342 9.98126 9.26784C9.98126 10.3823 10.8847 11.2857 11.9991 11.2857C13.1135 11.2857 14.0169 10.3823 14.0169 9.26784C14.0169 8.15342 13.1135 7.25 11.9991 7.25ZM8.48126 9.26784C8.48126 7.32499 10.0563 5.75 11.9991 5.75C13.9419 5.75 15.5169 7.32499 15.5169 9.26784C15.5169 11.2107 13.9419 12.7857 11.9991 12.7857C10.0563 12.7857 8.48126 11.2107 8.48126 9.26784Z"
                                    fill=""></path>
                            </svg>

                            <span class="menu-item-text" :class="sidebarToggle ? 'lg:hidden' : ''">
                                User Profile
                            </span>
                        </a>
                    </li>
                    <!-- Menu Item Profile -->

                    <!-- Menu Item Forms -->
                    <li>
                        <a href="#" @click.prevent="selected = (selected === 'Forms' ? '':'Forms')"
                            class="menu-item group"
                            :class="(selected === 'Forms') || (page === 'formElements' || page === 'formLayout' ||
                                page === 'proFormElements' || page === 'proFormLayout') ? 'menu-item-active' :
                            'menu-item-inactive'">
                            <svg :class="(selected === 'Forms') || (page === 'formElements' || page === 'formLayout' ||
                                page === 'proFormElements' || page === 'proFormLayout') ?
                            'menu-item-icon-active' : 'menu-item-icon-inactive'"
                                width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M5.5 3.25C4.25736 3.25 3.25 4.25736 3.25 5.5V18.5C3.25 19.7426 4.25736 20.75 5.5 20.75H18.5001C19.7427 20.75 20.7501 19.7426 20.7501 18.5V5.5C20.7501 4.25736 19.7427 3.25 18.5001 3.25H5.5ZM4.75 5.5C4.75 5.08579 5.08579 4.75 5.5 4.75H18.5001C18.9143 4.75 19.2501 5.08579 19.2501 5.5V18.5C19.2501 18.9142 18.9143 19.25 18.5001 19.25H5.5C5.08579 19.25 4.75 18.9142 4.75 18.5V5.5ZM6.25005 9.7143C6.25005 9.30008 6.58583 8.9643 7.00005 8.9643L17 8.96429C17.4143 8.96429 17.75 9.30008 17.75 9.71429C17.75 10.1285 17.4143 10.4643 17 10.4643L7.00005 10.4643C6.58583 10.4643 6.25005 10.1285 6.25005 9.7143ZM6.25005 14.2857C6.25005 13.8715 6.58583 13.5357 7.00005 13.5357H17C17.4143 13.5357 17.75 13.8715 17.75 14.2857C17.75 14.6999 17.4143 15.0357 17 15.0357H7.00005C6.58583 15.0357 6.25005 14.6999 6.25005 14.2857Z"
                                    fill="" />
                            </svg>

                            <span class="menu-item-text" :class="sidebarToggle ? 'lg:hidden' : ''">
                                Forms
                            </span>

                            <svg class="menu-item-arrow absolute right-2.5 top-1/2 -translate-y-1/2 stroke-current"
                                :class="[(selected === 'Forms') ? 'menu-item-arrow-active' :
                                    'menu-item-arrow-inactive', sidebarToggle ? 'lg:hidden' : ''
                                ]"
                                width="20" height="20" viewBox="0 0 20 20" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M4.79175 7.39584L10.0001 12.6042L15.2084 7.39585" stroke=""
                                    stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </a>

                        <!-- Dropdown Menu Start -->
                        <div class="overflow-hidden transform translate"
                            :class="(selected === 'Forms') ? 'block' : 'hidden'">
                            <ul :class="sidebarToggle ? 'lg:hidden' : 'flex'"
                                class="flex flex-col gap-1 mt-2 menu-dropdown pl-9">
                                <li>
                                    <a href="form-elements.html" class="menu-dropdown-item group"
                                        :class="page === 'formElements' ? 'menu-dropdown-item-active' :
                                            'menu-dropdown-item-inactive'">
                                        Form Elements
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <!-- Dropdown Menu End -->
                    </li>
                    <!-- Menu Item Forms -->

                    <!-- Menu Item Kelola User -->
                    <li>
                        <a href="#" @click.prevent="selected = (selected === 'KelolaUser' ? '' : 'KelolaUser')"
                            class="menu-item group"
                            :class="(selected === 'KelolaUser') || (page === 'siswa' || page === 'guru' ||
                                page === 'user') ?
                            'menu-item-active' : 'menu-item-inactive'">
                            <svg :class="(selected === 'KelolaUser') || (page === 'siswa' || page === 'guru' ||
                                page === 'user') ?
                            'menu-item-icon-active' : 'menu-item-icon-inactive'"
                                width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2ZM7 9c0-1.1.9-2 2-2s2 .9 2 2-.9 2-2 2-2-.9-2-2Zm5 10c-2.33 0-4.31-1.46-5.11-3.5.03-1.99 4-3.08 5.11-3.08 1.11 0 5.08 1.09 5.11 3.08C16.31 17.54 14.33 19 12 19Zm2-8c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2Z"
                                    fill="" />
                            </svg>

                            <span class="menu-item-text" :class="sidebarToggle ? 'lg:hidden' : ''">
                                Kelola User
                            </span>

                            <svg class="menu-item-arrow absolute right-2.5 top-1/2 -translate-y-1/2 stroke-current"
                                :class="[(selected === 'KelolaUser') ? 'menu-item-arrow-active' :
                                    'menu-item-arrow-inactive', sidebarToggle ? 'lg:hidden' : ''
                                ]"
                                width="20" height="20" viewBox="0 0 20 20" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M4.79175 7.39584L10.0001 12.6042L15.2084 7.39585" stroke=""
                                    stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </a>

                        <!-- Dropdown Menu Start -->
                        <div class="overflow-hidden transform translate"
                            :class="(selected === 'KelolaUser') ? 'block' : 'hidden'">
                            <ul :class="sidebarToggle ? 'lg:hidden' : 'flex'"
                                class="flex flex-col gap-1 mt-2 menu-dropdown pl-9">
                                <li>
                                    <a href="/admin/siswa" class="menu-dropdown-item group"
                                        :class="page === 'siswa' ? 'menu-dropdown-item-active' :
                                            'menu-dropdown-item-inactive'">
                                        Siswa
                                    </a>
                                </li>
                                <li>
                                    <a href="/admin/guru" class="menu-dropdown-item group"
                                        :class="page === 'guru' ? 'menu-dropdown-item-active' :
                                            'menu-dropdown-item-inactive'">
                                        Guru
                                    </a>
                                </li>
                                <li>
                                    <a href="/admin/user" class="menu-dropdown-item group"
                                        :class="page === 'user' ? 'menu-dropdown-item-active' :
                                            'menu-dropdown-item-inactive'">
                                        User
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <!-- Dropdown Menu End -->
                    </li>
                    <!-- Menu Item Kelola User End -->

                    <!-- Menu Item Kelola Kurikulum -->
                    <li>
                        <a href="#" @click.prevent="selected = (selected === 'Kurikulum' ? '' : 'Kurikulum')"
                            class="menu-item group"
                            :class="(selected === 'Kurikulum') || (page === 'mapel' || page === 'ekstrakurikuler' ||
                                page === 'p5') ? 'menu-item-active' : 'menu-item-inactive'">
                            <svg :class="(selected === 'Kurikulum') || (page === 'mapel' || page === 'ekstrakurikuler' ||
                                page === 'p5') ?
                            'menu-item-icon-active' : 'menu-item-icon-inactive'"
                                width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M5.5 3.25C4.25736 3.25 3.25 4.25736 3.25 5.5V18.5C3.25 19.7426 4.25736 20.75 5.5 20.75H18.5001C19.7427 20.75 20.7501 19.7426 20.7501 18.5V5.5C20.7501 4.25736 19.7427 3.25 18.5001 3.25H5.5ZM4.75 5.5C4.75 5.08579 5.08579 4.75 5.5 4.75H18.5001C18.9143 4.75 19.2501 5.08579 19.2501 5.5V18.5C19.2501 18.9142 18.9143 19.25 18.5001 19.25H5.5C5.08579 19.25 4.75 18.9142 4.75 18.5V5.5ZM6.25005 9.7143C6.25005 9.30008 6.58583 8.9643 7.00005 8.9643L17 8.96429C17.4143 8.96429 17.75 9.30008 17.75 9.71429C17.75 10.1285 17.4143 10.4643 17 10.4643L7.00005 10.4643C6.58583 10.4643 6.25005 10.1285 6.25005 9.7143ZM6.25005 14.2857C6.25005 13.8715 6.58583 13.5357 7.00005 13.5357H17C17.4143 13.5357 17.75 13.8715 17.75 14.2857C17.75 14.6999 17.4143 15.0357 17 15.0357H7.00005C6.58583 15.0357 6.25005 14.6999 6.25005 14.2857Z"
                                    fill="" />
                            </svg>

                            <span class="menu-item-text" :class="sidebarToggle ? 'lg:hidden' : ''">
                                Kelola Kurikulum
                            </span>

                            <svg class="menu-item-arrow absolute right-2.5 top-1/2 -translate-y-1/2 stroke-current"
                                :class="[(selected === 'Kurikulum') ? 'menu-item-arrow-active' : 'menu-item-arrow-inactive',
                                    sidebarToggle ? 'lg:hidden' : ''
                                ]"
                                width="20" height="20" viewBox="0 0 20 20" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M4.79175 7.39584L10.0001 12.6042L15.2084 7.39585" stroke=""
                                    stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </a>

                        <!-- Dropdown Menu Start -->
                        <div class="overflow-hidden transform translate"
                            :class="(selected === 'Kurikulum') ? 'block' : 'hidden'">
                            <ul :class="sidebarToggle ? 'lg:hidden' : 'flex'"
                                class="flex flex-col gap-1 mt-2 menu-dropdown pl-9">
                                <li>
                                    <a href="/kurikulum/tahun-semester" class="menu-dropdown-item group"
                                        :class="page === 'tahun-semester' ? 'menu-dropdown-item-active' :
                                            'menu-dropdown-item-inactive'">
                                        Tahun Pelajaran & Semester
                                    </a>
                                </li>
                                <li>
                                    <a href="/kurikulum/kelas" class="menu-dropdown-item group"
                                        :class="page === 'kelas' ? 'menu-dropdown-item-active' :
                                            'menu-dropdown-item-inactive'">
                                        Kelas
                                    </a>
                                </li>
                                <li>
                                    <a href="/kurikulum/mapel" class="menu-dropdown-item group"
                                        :class="page === 'mapel' ? 'menu-dropdown-item-active' :
                                            'menu-dropdown-item-inactive'">
                                        Mata Pelajaran
                                    </a>
                                </li>
                                <li>
                                    <a href="/kurikulum/ekstrakurikuler" class="menu-dropdown-item group"
                                        :class="page === 'ekstrakurikuler' ? 'menu-dropdown-item-active' :
                                            'menu-dropdown-item-inactive'">
                                        Ekstrakurikuler
                                    </a>
                                </li>
                                <li>
                                    <a href="/kurikulum/p5" class="menu-dropdown-item group"
                                        :class="page === 'p5' ? 'menu-dropdown-item-active' :
                                            'menu-dropdown-item-inactive'">
                                        Projek P5
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <!-- Dropdown Menu End -->
                    </li>


                    <!-- Menu Item Kelola Nilai -->
                    <li>
                        <a href="#"
                            @click.prevent="selected = (selected === 'KelolaNilai' ? '' : 'KelolaNilai')"
                            class="menu-item group"
                            :class="(selected === 'KelolaNilai') || (page === 'nilaiPengetahuan' ||
                                page === 'nilaiKeterampilan' || page === 'nilaiSikap') ?
                            'menu-item-active' : 'menu-item-inactive'">
                            <svg :class="(selected === 'KelolaNilai') || (page === 'nilaiPengetahuan' ||
                                page === 'nilaiKeterampilan' || page === 'nilaiSikap') ?
                            'menu-item-icon-active' : 'menu-item-icon-inactive'"
                                width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M4 4.5C4 3.67157 4.67157 3 5.5 3H18.5C19.3284 3 20 3.67157 20 4.5V19.5C20 20.3284 19.3284 21 18.5 21H5.5C4.67157 21 4 20.3284 4 19.5V4.5ZM6 5V19H18V5H6ZM8 7H10V9H8V7ZM8 11H10V13H8V11ZM8 15H10V17H8V15ZM12 7H16V9H12V7ZM12 11H16V13H12V11ZM12 15H16V17H12V15Z"
                                    fill="" />
                            </svg>

                            <span class="menu-item-text" :class="sidebarToggle ? 'lg:hidden' : ''">
                                Kelola Nilai
                            </span>

                            <svg class="menu-item-arrow absolute right-2.5 top-1/2 -translate-y-1/2 stroke-current"
                                :class="[(selected === 'KelolaNilai') ? 'menu-item-arrow-active' :
                                    'menu-item-arrow-inactive', sidebarToggle ? 'lg:hidden' : ''
                                ]"
                                width="20" height="20" viewBox="0 0 20 20" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M4.79175 7.39584L10.0001 12.6042L15.2084 7.39585" stroke=""
                                    stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </a>

                        <!-- Dropdown Menu Start -->
                        <div class="overflow-hidden transform translate"
                            :class="(selected === 'KelolaNilai') ? 'block' : 'hidden'">
                            <ul :class="sidebarToggle ? 'lg:hidden' : 'flex'"
                                class="flex flex-col gap-1 mt-2 menu-dropdown pl-9">
                                <li>
                                    <a href="/nilai/pengetahuan" class="menu-dropdown-item group"
                                        :class="page === 'nilaiPengetahuan' ? 'menu-dropdown-item-active' :
                                            'menu-dropdown-item-inactive'">
                                        Pengetahuan
                                    </a>
                                </li>
                                <li>
                                    <a href="/nilai/keterampilan" class="menu-dropdown-item group"
                                        :class="page === 'nilaiKeterampilan' ? 'menu-dropdown-item-active' :
                                            'menu-dropdown-item-inactive'">
                                        Keterampilan
                                    </a>
                                </li>
                                <li>
                                    <a href="/nilai/sikap" class="menu-dropdown-item group"
                                        :class="page === 'nilaiSikap' ? 'menu-dropdown-item-active' :
                                            'menu-dropdown-item-inactive'">
                                        Sikap
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <!-- Dropdown Menu End -->
                    </li>
                    <!-- Menu Item Kelola Nilai End -->


                    <!-- Menu Item Kelola Rapor -->
                    <li>
                        <a href="#"
                            @click.prevent="selected = (selected === 'KelolaRapor' ? '' : 'KelolaRapor')"
                            class="menu-item group"
                            :class="(selected === 'KelolaRapor') || (page === 'cetakRapor' || page === 'dataRapor' ||
                                page === 'arsipRapor') ?
                            'menu-item-active' : 'menu-item-inactive'">
                            <svg :class="(selected === 'KelolaRapor') || (page === 'cetakRapor' || page === 'dataRapor' ||
                                page === 'arsipRapor') ?
                            'menu-item-icon-active' : 'menu-item-icon-inactive'"
                                width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M5 3C3.89543 3 3 3.89543 3 5V19C3 20.1046 3.89543 21 5 21H19C20.1046 21 21 20.1046 21 19V8.82843C21 8.29799 20.7893 7.78929 20.4142 7.41421L15.5858 2.58579C15.2107 2.21071 14.702 2 14.1716 2H5ZM13 3.5V8C13 8.55228 13.4477 9 14 9H18.5L13 3.5ZM5 5H12V8C12 9.10457 12.8954 10 14 10H19V19H5V5ZM7 13H17V14.5H7V13ZM7 16H14V17.5H7V16Z"
                                    fill="" />
                            </svg>

                            <span class="menu-item-text" :class="sidebarToggle ? 'lg:hidden' : ''">
                                Kelola Rapor
                            </span>

                            <svg class="menu-item-arrow absolute right-2.5 top-1/2 -translate-y-1/2 stroke-current"
                                :class="[(selected === 'KelolaRapor') ? 'menu-item-arrow-active' :
                                    'menu-item-arrow-inactive', sidebarToggle ? 'lg:hidden' : ''
                                ]"
                                width="20" height="20" viewBox="0 0 20 20" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M4.79175 7.39584L10.0001 12.6042L15.2084 7.39585" stroke=""
                                    stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </a>

                        <!-- Dropdown Menu Start -->
                        <div class="overflow-hidden transform translate"
                            :class="(selected === 'KelolaRapor') ? 'block' : 'hidden'">
                            <ul :class="sidebarToggle ? 'lg:hidden' : 'flex'"
                                class="flex flex-col gap-1 mt-2 menu-dropdown pl-9">
                                <li>
                                    <a href="/rapor/cetak" class="menu-dropdown-item group"
                                        :class="page === 'cetakRapor' ? 'menu-dropdown-item-active' :
                                            'menu-dropdown-item-inactive'">
                                        Cetak Rapor
                                    </a>
                                </li>
                                <li>
                                    <a href="/rapor/data" class="menu-dropdown-item group"
                                        :class="page === 'dataRapor' ? 'menu-dropdown-item-active' :
                                            'menu-dropdown-item-inactive'">
                                        Data Rapor
                                    </a>
                                </li>
                                <li>
                                    <a href="/rapor/arsip" class="menu-dropdown-item group"
                                        :class="page === 'arsipRapor' ? 'menu-dropdown-item-active' :
                                            'menu-dropdown-item-inactive'">
                                        Arsip Rapor
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <!-- Dropdown Menu End -->
                    </li>
                    <!-- Menu Item Kelola Rapor End -->

                </ul>
            </div>

            <!-- NILAI Group -->
            <div>
                <h3 class="mb-4 text-xs uppercase leading-[20px] text-gray-400">
                    <span class="menu-group-title" :class="sidebarToggle ? 'lg:hidden' : ''">
                        NILAI
                    </span>

                    <svg :class="sidebarToggle ? 'lg:block hidden' : 'hidden'"
                        class="mx-auto fill-current menu-group-icon" width="24" height="24"
                        viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M5.99915 10.2451C6.96564 10.2451 7.74915 11.0286 7.74915 11.9951V12.0051C7.74915 12.9716 6.96564 13.7551 5.99915 13.7551C5.03265 13.7551 4.24915 12.9716 4.24915 12.0051V11.9951C4.24915 11.0286 5.03265 10.2451 5.99915 10.2451ZM17.9991 10.2451C18.9656 10.2451 19.7491 11.0286 19.7491 11.9951V12.0051C19.7491 12.9716 18.9656 13.7551 17.9991 13.7551C17.0326 13.7551 16.2491 12.9716 16.2491 12.0051V11.9951C16.2491 11.0286 17.0326 10.2451 17.9991 10.2451ZM13.7491 11.9951C13.7491 11.0286 12.9656 10.2451 11.9991 10.2451C11.0326 10.2451 10.2491 11.0286 10.2491 11.9951V12.0051C10.2491 12.9716 11.0326 13.7551 11.9991 13.7551C12.9656 13.7551 13.7491 12.9716 13.7491 12.0051V11.9951Z"
                            fill="" />
                    </svg>
                </h3>

                <ul class="flex flex-col gap-4 mb-6">
                    <!-- Menu Item Charts -->
                    <li>
                        <a href="#" @click.prevent="selected = (selected === 'Charts' ? '':'Charts')"
                            class="menu-item group"
                            :class="(selected === 'Charts') || (page === 'lineChart' || page === 'barChart' ||
                                page === 'pieChart') ? 'menu-item-active' : 'menu-item-inactive'">
                            <svg :class="(selected === 'Charts') || (page === 'lineChart' || page === 'barChart' ||
                                page === 'pieChart') ? 'menu-item-icon-active' : 'menu-item-icon-inactive'"
                                width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M12 2C11.5858 2 11.25 2.33579 11.25 2.75V12C11.25 12.4142 11.5858 12.75 12 12.75H21.25C21.6642 12.75 22 12.4142 22 12C22 6.47715 17.5228 2 12 2ZM12.75 11.25V3.53263C13.2645 3.57761 13.7659 3.66843 14.25 3.80098V3.80099C15.6929 4.19606 16.9827 4.96184 18.0104 5.98959C19.0382 7.01734 19.8039 8.30707 20.199 9.75C20.3316 10.2341 20.4224 10.7355 20.4674 11.25H12.75ZM2 12C2 7.25083 5.31065 3.27489 9.75 2.25415V3.80099C6.14748 4.78734 3.5 8.0845 3.5 12C3.5 16.6944 7.30558 20.5 12 20.5C15.9155 20.5 19.2127 17.8525 20.199 14.25H21.7459C20.7251 18.6894 16.7492 22 12 22C6.47715 22 2 17.5229 2 12Z"
                                    fill="" />
                            </svg>

                            <span class="menu-item-text" :class="sidebarToggle ? 'lg:hidden' : ''">
                                Charts
                            </span>

                            <svg class="menu-item-arrow absolute right-2.5 top-1/2 -translate-y-1/2 stroke-current"
                                :class="[(selected === 'Charts') ? 'menu-item-arrow-active' :
                                    'menu-item-arrow-inactive', sidebarToggle ? 'lg:hidden' : ''
                                ]"
                                width="20" height="20" viewBox="0 0 20 20" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M4.79175 7.39584L10.0001 12.6042L15.2084 7.39585" stroke=""
                                    stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </a>

                        <!-- Dropdown Menu Start -->
                        <div class="overflow-hidden transform translate"
                            :class="(selected === 'Charts') ? 'block' : 'hidden'">
                            <ul :class="sidebarToggle ? 'lg:hidden' : 'flex'"
                                class="flex flex-col gap-1 mt-2 menu-dropdown pl-9">
                                <li>
                                    <a href="line-chart.html" class="menu-dropdown-item group"
                                        :class="page === 'lineChart' ? 'menu-dropdown-item-active' :
                                            'menu-dropdown-item-inactive'">
                                        Line Chart
                                    </a>
                                </li>
                                <li>
                                    <a href="bar-chart.html" class="menu-dropdown-item group"
                                        :class="page === 'barChart' ? 'menu-dropdown-item-active' :
                                            'menu-dropdown-item-inactive'">
                                        Bar Chart
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <!-- Dropdown Menu End -->
                    </li>
                    <!-- Menu Item Charts -->

                    <!-- Menu Item Ui Elements -->
                    <li>
                        <a href="#" @click.prevent="selected = (selected === 'UIElements' ? '':'UIElements')"
                            class="menu-item group"
                            :class="(selected === 'UIElements') || (page === 'alerts' || page === 'avatars' ||
                                page === 'badge' || page === 'buttons' || page === 'buttonsGroup' ||
                                page === 'cards' || page === 'carousel' || page === 'dropdowns' ||
                                page === 'images' || page === 'list' || page === 'modals' ||
                                page === 'videos') ? 'menu-item-active' : 'menu-item-inactive'">
                            <svg :class="(selected === 'UIElements') || (page === 'alerts' || page === 'avatars' ||
                                page === 'badge' || page === 'breadcrumb' || page === 'buttons' ||
                                page === 'buttonsGroup' || page === 'cards' || page === 'carousel' ||
                                page === 'dropdowns' || page === 'images' || page === 'list' ||
                                page === 'modals' || page === 'notifications' || page === 'popovers' ||
                                page === 'progress' || page === 'spinners' || page === 'tooltips' ||
                                page === 'videos') ? 'menu-item-icon-active' : 'menu-item-icon-inactive'"
                                width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M11.665 3.75618C11.8762 3.65061 12.1247 3.65061 12.3358 3.75618L18.7807 6.97853L12.3358 10.2009C12.1247 10.3064 11.8762 10.3064 11.665 10.2009L5.22014 6.97853L11.665 3.75618ZM4.29297 8.19199V16.0946C4.29297 16.3787 4.45347 16.6384 4.70757 16.7654L11.25 20.0365V11.6512C11.1631 11.6205 11.0777 11.5843 10.9942 11.5425L4.29297 8.19199ZM12.75 20.037L19.2933 16.7654C19.5474 16.6384 19.7079 16.3787 19.7079 16.0946V8.19199L13.0066 11.5425C12.9229 11.5844 12.8372 11.6207 12.75 11.6515V20.037ZM13.0066 2.41453C12.3732 2.09783 11.6277 2.09783 10.9942 2.41453L4.03676 5.89316C3.27449 6.27429 2.79297 7.05339 2.79297 7.90563V16.0946C2.79297 16.9468 3.27448 17.7259 4.03676 18.1071L10.9942 21.5857L11.3296 20.9149L10.9942 21.5857C11.6277 21.9024 12.3732 21.9024 13.0066 21.5857L19.9641 18.1071C20.7264 17.7259 21.2079 16.9468 21.2079 16.0946V7.90563C21.2079 7.05339 20.7264 6.27429 19.9641 5.89316L13.0066 2.41453Z"
                                    fill="" />
                            </svg>

                            <span class="menu-item-text" :class="sidebarToggle ? 'lg:hidden' : ''">
                                UI Elements
                            </span>

                            <svg class="menu-item-arrow absolute right-2.5 top-1/2 -translate-y-1/2 stroke-current"
                                :class="[(selected === 'UIElements') ? 'menu-item-arrow-active' :
                                    'menu-item-arrow-inactive', sidebarToggle ? 'lg:hidden' : ''
                                ]"
                                width="20" height="20" viewBox="0 0 20 20" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M4.79175 7.39584L10.0001 12.6042L15.2084 7.39585" stroke=""
                                    stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </a>

                        <!-- Dropdown Menu Start -->
                        <div class="overflow-hidden transform translate"
                            :class="(selected === 'UIElements') ? 'block' : 'hidden'">
                            <ul :class="sidebarToggle ? 'lg:hidden' : 'flex'"
                                class="flex flex-col gap-1 mt-2 menu-dropdown pl-9">
                                <li>
                                    <a href="alerts.html" class="menu-dropdown-item group"
                                        :class="page === 'alerts' ? 'menu-dropdown-item-active' :
                                            'menu-dropdown-item-inactive'">
                                        Alerts
                                    </a>
                                </li>
                                <li>
                                    <a href="avatars.html" class="menu-dropdown-item group"
                                        :class="page === 'avatars' ? 'menu-dropdown-item-active' :
                                            'menu-dropdown-item-inactive'">
                                        Avatars
                                    </a>
                                </li>
                                <li>
                                    <a href="badge.html" class="menu-dropdown-item group"
                                        :class="page === 'badge' ? 'menu-dropdown-item-active' :
                                            'menu-dropdown-item-inactive'">
                                        Badges
                                    </a>
                                </li>
                                <li>
                                    <a href="buttons.html" class="menu-dropdown-item group"
                                        :class="page === 'buttons' ? 'menu-dropdown-item-active' :
                                            'menu-dropdown-item-inactive'">
                                        Buttons
                                    </a>
                                </li>
                                <li>
                                    <a href="images.html" class="menu-dropdown-item group"
                                        :class="page === 'images' ? 'menu-dropdown-item-active' :
                                            'menu-dropdown-item-inactive'">
                                        Images
                                    </a>
                                </li>
                                <li>
                                    <a href="videos.html" class="menu-dropdown-item group"
                                        :class="page === 'videos' ? 'menu-dropdown-item-active' :
                                            'menu-dropdown-item-inactive'">
                                        Videos
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <!-- Dropdown Menu End -->
                    </li>
                    <!-- Menu Item Ui Elements -->

                    <!-- Menu Item Authentication -->
                    <li>
                        <a href="#"
                            @click.prevent="selected = (selected === 'Authentication' ? '':'Authentication')"
                            class="menu-item group"
                            :class="(selected === 'Authentication') || (page === 'basicChart' ||
                                page === 'advancedChart') ? 'menu-item-active' : 'menu-item-inactive'">
                            <svg :class="(selected === 'Authentication') || (page === 'basicChart' ||
                                page === 'advancedChart') ? 'menu-item-icon-active' : 'menu-item-icon-inactive'"
                                width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M14 2.75C14 2.33579 14.3358 2 14.75 2C15.1642 2 15.5 2.33579 15.5 2.75V5.73291L17.75 5.73291H19C19.4142 5.73291 19.75 6.0687 19.75 6.48291C19.75 6.89712 19.4142 7.23291 19 7.23291H18.5L18.5 12.2329C18.5 15.5691 15.9866 18.3183 12.75 18.6901V21.25C12.75 21.6642 12.4142 22 12 22C11.5858 22 11.25 21.6642 11.25 21.25V18.6901C8.01342 18.3183 5.5 15.5691 5.5 12.2329L5.5 7.23291H5C4.58579 7.23291 4.25 6.89712 4.25 6.48291C4.25 6.0687 4.58579 5.73291 5 5.73291L6.25 5.73291L8.5 5.73291L8.5 2.75C8.5 2.33579 8.83579 2 9.25 2C9.66421 2 10 2.33579 10 2.75L10 5.73291L14 5.73291V2.75ZM7 7.23291L7 12.2329C7 14.9943 9.23858 17.2329 12 17.2329C14.7614 17.2329 17 14.9943 17 12.2329L17 7.23291L7 7.23291Z"
                                    fill="" />
                            </svg>

                            <span class="menu-item-text" :class="sidebarToggle ? 'lg:hidden' : ''">
                                Authentication
                            </span>

                            <svg class="menu-item-arrow absolute right-2.5 top-1/2 -translate-y-1/2 stroke-current"
                                :class="[(selected === 'Authentication') ? 'menu-item-arrow-active' :
                                    'menu-item-arrow-inactive', sidebarToggle ? 'lg:hidden' : ''
                                ]"
                                width="20" height="20" viewBox="0 0 20 20" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M4.79175 7.39584L10.0001 12.6042L15.2084 7.39585" stroke=""
                                    stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </a>

                        <!-- Dropdown Menu Start -->
                        <div class="overflow-hidden transform translate"
                            :class="(selected === 'Authentication') ? 'block' : 'hidden'">
                            <ul :class="sidebarToggle ? 'lg:hidden' : 'flex'"
                                class="flex flex-col gap-1 mt-2 menu-dropdown pl-9">
                                <li>
                                    <a href="signin.html" class="menu-dropdown-item group"
                                        :class="page === 'signin' ? 'menu-dropdown-item-active' :
                                            'menu-dropdown-item-inactive'">
                                        Sign In
                                    </a>
                                </li>
                                <li>
                                    <a href="signup.html" class="menu-dropdown-item group"
                                        :class="page === 'signup' ? 'menu-dropdown-item-active' :
                                            'menu-dropdown-item-inactive'">
                                        Sign Up
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <!-- Dropdown Menu End -->
                    </li>
                    <!-- Menu Item Authentication -->
                </ul>
            </div>

            <!-- NILAI Group -->
            <div>
                <h3 class="mb-4 text-xs uppercase leading-[20px] text-gray-400">
                    <span class="menu-group-title" :class="sidebarToggle ? 'lg:hidden' : ''">
                        NILAI
                    </span>

                    <svg :class="sidebarToggle ? 'lg:block hidden' : 'hidden'"
                        class="mx-auto fill-current menu-group-icon" width="24" height="24"
                        viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M5.99915 10.2451C6.96564 10.2451 7.74915 11.0286 7.74915 11.9951V12.0051C7.74915 12.9716 6.96564 13.7551 5.99915 13.7551C5.03265 13.7551 4.24915 12.9716 4.24915 12.0051V11.9951C4.24915 11.0286 5.03265 10.2451 5.99915 10.2451ZM17.9991 10.2451C18.9656 10.2451 19.7491 11.0286 19.7491 11.9951V12.0051C19.7491 12.9716 18.9656 13.7551 17.9991 13.7551C17.0326 13.7551 16.2491 12.9716 16.2491 12.0051V11.9951C16.2491 11.0286 17.0326 10.2451 17.9991 10.2451ZM13.7491 11.9951C13.7491 11.0286 12.9656 10.2451 11.9991 10.2451C11.0326 10.2451 10.2491 11.0286 10.2491 11.9951V12.0051C10.2491 12.9716 11.0326 13.7551 11.9991 13.7551C12.9656 13.7551 13.7491 12.9716 13.7491 12.0051V11.9951Z"
                            fill="" />
                    </svg>
                </h3>

                <ul class="flex flex-col gap-4 mb-6">
                    <!-- Menu Item Charts -->
                    <li>
                        <a href="#" @click.prevent="selected = (selected === 'Charts' ? '':'Charts')"
                            class="menu-item group"
                            :class="(selected === 'Charts') || (page === 'lineChart' || page === 'barChart' ||
                                page === 'pieChart') ? 'menu-item-active' : 'menu-item-inactive'">
                            <svg :class="(selected === 'Charts') || (page === 'lineChart' || page === 'barChart' ||
                                page === 'pieChart') ? 'menu-item-icon-active' : 'menu-item-icon-inactive'"
                                width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M12 2C11.5858 2 11.25 2.33579 11.25 2.75V12C11.25 12.4142 11.5858 12.75 12 12.75H21.25C21.6642 12.75 22 12.4142 22 12C22 6.47715 17.5228 2 12 2ZM12.75 11.25V3.53263C13.2645 3.57761 13.7659 3.66843 14.25 3.80098V3.80099C15.6929 4.19606 16.9827 4.96184 18.0104 5.98959C19.0382 7.01734 19.8039 8.30707 20.199 9.75C20.3316 10.2341 20.4224 10.7355 20.4674 11.25H12.75ZM2 12C2 7.25083 5.31065 3.27489 9.75 2.25415V3.80099C6.14748 4.78734 3.5 8.0845 3.5 12C3.5 16.6944 7.30558 20.5 12 20.5C15.9155 20.5 19.2127 17.8525 20.199 14.25H21.7459C20.7251 18.6894 16.7492 22 12 22C6.47715 22 2 17.5229 2 12Z"
                                    fill="" />
                            </svg>

                            <span class="menu-item-text" :class="sidebarToggle ? 'lg:hidden' : ''">
                                Charts
                            </span>

                            <svg class="menu-item-arrow absolute right-2.5 top-1/2 -translate-y-1/2 stroke-current"
                                :class="[(selected === 'Charts') ? 'menu-item-arrow-active' :
                                    'menu-item-arrow-inactive', sidebarToggle ? 'lg:hidden' : ''
                                ]"
                                width="20" height="20" viewBox="0 0 20 20" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M4.79175 7.39584L10.0001 12.6042L15.2084 7.39585" stroke=""
                                    stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </a>

                        <!-- Dropdown Menu Start -->
                        <div class="overflow-hidden transform translate"
                            :class="(selected === 'Charts') ? 'block' : 'hidden'">
                            <ul :class="sidebarToggle ? 'lg:hidden' : 'flex'"
                                class="flex flex-col gap-1 mt-2 menu-dropdown pl-9">
                                <li>
                                    <a href="line-chart.html" class="menu-dropdown-item group"
                                        :class="page === 'lineChart' ? 'menu-dropdown-item-active' :
                                            'menu-dropdown-item-inactive'">
                                        Line Chart
                                    </a>
                                </li>
                                <li>
                                    <a href="bar-chart.html" class="menu-dropdown-item group"
                                        :class="page === 'barChart' ? 'menu-dropdown-item-active' :
                                            'menu-dropdown-item-inactive'">
                                        Bar Chart
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <!-- Dropdown Menu End -->
                    </li>
                    <!-- Menu Item Charts -->

                    <!-- Menu Item Ui Elements -->
                    <li>
                        <a href="#" @click.prevent="selected = (selected === 'UIElements' ? '':'UIElements')"
                            class="menu-item group"
                            :class="(selected === 'UIElements') || (page === 'alerts' || page === 'avatars' ||
                                page === 'badge' || page === 'buttons' || page === 'buttonsGroup' ||
                                page === 'cards' || page === 'carousel' || page === 'dropdowns' ||
                                page === 'images' || page === 'list' || page === 'modals' ||
                                page === 'videos') ? 'menu-item-active' : 'menu-item-inactive'">
                            <svg :class="(selected === 'UIElements') || (page === 'alerts' || page === 'avatars' ||
                                page === 'badge' || page === 'breadcrumb' || page === 'buttons' ||
                                page === 'buttonsGroup' || page === 'cards' || page === 'carousel' ||
                                page === 'dropdowns' || page === 'images' || page === 'list' ||
                                page === 'modals' || page === 'notifications' || page === 'popovers' ||
                                page === 'progress' || page === 'spinners' || page === 'tooltips' ||
                                page === 'videos') ? 'menu-item-icon-active' : 'menu-item-icon-inactive'"
                                width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M11.665 3.75618C11.8762 3.65061 12.1247 3.65061 12.3358 3.75618L18.7807 6.97853L12.3358 10.2009C12.1247 10.3064 11.8762 10.3064 11.665 10.2009L5.22014 6.97853L11.665 3.75618ZM4.29297 8.19199V16.0946C4.29297 16.3787 4.45347 16.6384 4.70757 16.7654L11.25 20.0365V11.6512C11.1631 11.6205 11.0777 11.5843 10.9942 11.5425L4.29297 8.19199ZM12.75 20.037L19.2933 16.7654C19.5474 16.6384 19.7079 16.3787 19.7079 16.0946V8.19199L13.0066 11.5425C12.9229 11.5844 12.8372 11.6207 12.75 11.6515V20.037ZM13.0066 2.41453C12.3732 2.09783 11.6277 2.09783 10.9942 2.41453L4.03676 5.89316C3.27449 6.27429 2.79297 7.05339 2.79297 7.90563V16.0946C2.79297 16.9468 3.27448 17.7259 4.03676 18.1071L10.9942 21.5857L11.3296 20.9149L10.9942 21.5857C11.6277 21.9024 12.3732 21.9024 13.0066 21.5857L19.9641 18.1071C20.7264 17.7259 21.2079 16.9468 21.2079 16.0946V7.90563C21.2079 7.05339 20.7264 6.27429 19.9641 5.89316L13.0066 2.41453Z"
                                    fill="" />
                            </svg>

                            <span class="menu-item-text" :class="sidebarToggle ? 'lg:hidden' : ''">
                                UI Elements
                            </span>

                            <svg class="menu-item-arrow absolute right-2.5 top-1/2 -translate-y-1/2 stroke-current"
                                :class="[(selected === 'UIElements') ? 'menu-item-arrow-active' :
                                    'menu-item-arrow-inactive', sidebarToggle ? 'lg:hidden' : ''
                                ]"
                                width="20" height="20" viewBox="0 0 20 20" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M4.79175 7.39584L10.0001 12.6042L15.2084 7.39585" stroke=""
                                    stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </a>

                        <!-- Dropdown Menu Start -->
                        <div class="overflow-hidden transform translate"
                            :class="(selected === 'UIElements') ? 'block' : 'hidden'">
                            <ul :class="sidebarToggle ? 'lg:hidden' : 'flex'"
                                class="flex flex-col gap-1 mt-2 menu-dropdown pl-9">
                                <li>
                                    <a href="alerts.html" class="menu-dropdown-item group"
                                        :class="page === 'alerts' ? 'menu-dropdown-item-active' :
                                            'menu-dropdown-item-inactive'">
                                        Alerts
                                    </a>
                                </li>
                                <li>
                                    <a href="avatars.html" class="menu-dropdown-item group"
                                        :class="page === 'avatars' ? 'menu-dropdown-item-active' :
                                            'menu-dropdown-item-inactive'">
                                        Avatars
                                    </a>
                                </li>
                                <li>
                                    <a href="badge.html" class="menu-dropdown-item group"
                                        :class="page === 'badge' ? 'menu-dropdown-item-active' :
                                            'menu-dropdown-item-inactive'">
                                        Badges
                                    </a>
                                </li>
                                <li>
                                    <a href="buttons.html" class="menu-dropdown-item group"
                                        :class="page === 'buttons' ? 'menu-dropdown-item-active' :
                                            'menu-dropdown-item-inactive'">
                                        Buttons
                                    </a>
                                </li>
                                <li>
                                    <a href="images.html" class="menu-dropdown-item group"
                                        :class="page === 'images' ? 'menu-dropdown-item-active' :
                                            'menu-dropdown-item-inactive'">
                                        Images
                                    </a>
                                </li>
                                <li>
                                    <a href="videos.html" class="menu-dropdown-item group"
                                        :class="page === 'videos' ? 'menu-dropdown-item-active' :
                                            'menu-dropdown-item-inactive'">
                                        Videos
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <!-- Dropdown Menu End -->
                    </li>
                    <!-- Menu Item Ui Elements -->

                    <!-- Menu Item Authentication -->
                    <li>
                        <a href="#"
                            @click.prevent="selected = (selected === 'Authentication' ? '':'Authentication')"
                            class="menu-item group"
                            :class="(selected === 'Authentication') || (page === 'basicChart' ||
                                page === 'advancedChart') ? 'menu-item-active' : 'menu-item-inactive'">
                            <svg :class="(selected === 'Authentication') || (page === 'basicChart' ||
                                page === 'advancedChart') ? 'menu-item-icon-active' : 'menu-item-icon-inactive'"
                                width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M14 2.75C14 2.33579 14.3358 2 14.75 2C15.1642 2 15.5 2.33579 15.5 2.75V5.73291L17.75 5.73291H19C19.4142 5.73291 19.75 6.0687 19.75 6.48291C19.75 6.89712 19.4142 7.23291 19 7.23291H18.5L18.5 12.2329C18.5 15.5691 15.9866 18.3183 12.75 18.6901V21.25C12.75 21.6642 12.4142 22 12 22C11.5858 22 11.25 21.6642 11.25 21.25V18.6901C8.01342 18.3183 5.5 15.5691 5.5 12.2329L5.5 7.23291H5C4.58579 7.23291 4.25 6.89712 4.25 6.48291C4.25 6.0687 4.58579 5.73291 5 5.73291L6.25 5.73291L8.5 5.73291L8.5 2.75C8.5 2.33579 8.83579 2 9.25 2C9.66421 2 10 2.33579 10 2.75L10 5.73291L14 5.73291V2.75ZM7 7.23291L7 12.2329C7 14.9943 9.23858 17.2329 12 17.2329C14.7614 17.2329 17 14.9943 17 12.2329L17 7.23291L7 7.23291Z"
                                    fill="" />
                            </svg>

                            <span class="menu-item-text" :class="sidebarToggle ? 'lg:hidden' : ''">
                                Authentication
                            </span>

                            <svg class="menu-item-arrow absolute right-2.5 top-1/2 -translate-y-1/2 stroke-current"
                                :class="[(selected === 'Authentication') ? 'menu-item-arrow-active' :
                                    'menu-item-arrow-inactive', sidebarToggle ? 'lg:hidden' : ''
                                ]"
                                width="20" height="20" viewBox="0 0 20 20" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M4.79175 7.39584L10.0001 12.6042L15.2084 7.39585" stroke=""
                                    stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </a>

                        <!-- Dropdown Menu Start -->
                        <div class="overflow-hidden transform translate"
                            :class="(selected === 'Authentication') ? 'block' : 'hidden'">
                            <ul :class="sidebarToggle ? 'lg:hidden' : 'flex'"
                                class="flex flex-col gap-1 mt-2 menu-dropdown pl-9">
                                <li>
                                    <a href="signin.html" class="menu-dropdown-item group"
                                        :class="page === 'signin' ? 'menu-dropdown-item-active' :
                                            'menu-dropdown-item-inactive'">
                                        Sign In
                                    </a>
                                </li>
                                <li>
                                    <a href="signup.html" class="menu-dropdown-item group"
                                        :class="page === 'signup' ? 'menu-dropdown-item-active' :
                                            'menu-dropdown-item-inactive'">
                                        Sign Up
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <!-- Dropdown Menu End -->
                    </li>
                    <!-- Menu Item Authentication -->
                </ul>
            </div>
        </nav>
        <!-- Sidebar Menu -->

    </div>
</aside>
