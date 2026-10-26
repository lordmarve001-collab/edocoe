"use strict";
function selectstate() {
	var a = document.getElementById("state").value;
	var state;
if (a=='Abia') {
		 state='<option disabled selected>Select L.G.A</option><option>aba North</option><option>aba South</option><option>Arochukwu</option><option>Bende</option><option>Ikwuano</option><option>Isiala Ngwa North</option><option>Isiala Ngwa South</option><option>Isuikwuato</option><option>Obi Ngwa</option><option>Ohafia</option><option>Osisioma Ngwa</option><option>Ugwunagbo</option><option>Ukwa West</option><option>Umuahia North</option><option>Umu Nneochi</option>';
	}
	else if(a=='Adamawa'){
	state='<option disabled selected>Select L.G.A</option><option>Demsa</option><option>Fufore</option><option>Ganaye</option><option>Gireri</option><option>Gombi</option><option>Guyuk</option><option>Hong</option><option>Jada</option><option>Lamurde</option><option>Madagali</option><option>Maiha</option><option>Mayo-Belwa</option><option>Michika</option><option>Mubi North</option><option>Mubi South</option><option>Numan</option><option>Shelleng</option><option>Song</option><option>Toungo</option>';
	}
	else if (a=='Akwa-ibom') {
		 state='<option disabled selected>Select L.G.A</option><option>Abak</option><option>Eastern Obolo</option><option>Eket</option><option>Esit-Eket</option><option>Essien Udim</option><option>Etim-Ekpo</option><option>Etinan</option><option>Ibeno</option><option>Ibesikpo-Asutan</option><option>Ibiono-Ibom</option><option>Ika</option><option>Ikono</option><option>Ikot Abasi</option><option>Ikot Ekpene</option><option>Ini</option><option>Itu</option><option>Mbo</option><option>Mkpat-Enin</option><option>Nsit-Atai</option><option>Nsit-Ibom</option><option>Nsit-Ubium</option><option>Obot-Akara</option><option>Okobo</option><option>Onna</option><option>Oron</option><option>Oruk Anam</option><option>Ukanafun</option><option>Udung-Uko</option><option>Uruan</option><option>Urue-Offong/Oruko</option><option>Uyo</option>';
	} else if (a=='Anambra') {
		 state='<option disabled selected>Select L.G.A</option><option>Aguata</option><option>Anambra East</option><option>Anambra West</option><option>Anaocha</option><option>Awka North</option><option>Awka South</option><option>Ayamelum</option><option>Dunukofia</option><option>Ekwusigo</option><option>Idemili North</option><option>Idemili south</option><option>Ihiala</option><option>Njikoka</option><option>Nnewi North</option><option>Nnewi South</option><option>Ogbaru</option><option>Onitsha North</option><option>Onitsha South</option><option>Orumba North</option><option>Orumba South</option><option>Oyi</option>';
	}
	else if (a=='Bauchi') {
		 state='<option disabled selected>Select L.G.A</option><option>Bauchi</option><option>Bogoro</option><option>Damban</option><option>DarazoDarazo</option><option>Dass</option><option>Ganjuwa</option><option>Giade</option><option>Itas/Gadau</option><option>Jama-are</option><option>Katagum</option><option>Kirfi</option><option>Misau</option><option>Ningi</option><option>Shira</option><option>Tafawa-Balewa</option><option>Toro</option><option>Warji</option><option>Zaki</option>';
	}
	else if (a=='Bayelsa') {
		state='<option disabled selected>Select L.G.A</option><option>Brass</option><option>Ekeremor</option><option>Kolokuma/Opokuma</option><option>Nembe</option><option>Ogbia</option><option>Sagbama</option><option>Southern-Ijaw</option><option>Yenagoa</option>';
	}
	else if (a=='Benue') {
		state='<option disabled selected>Select L.G.A</option><option>Ado</option><option>Agatu</option><option>Apa</option><option>Buruku</option><option>Gboko</option><option>Guma</option><option>Gwer East</option><option>Gwer West</option><option>Katsina-Ala</option><option>Konshisha</option><option>Kwande</option><option>Logo</option><option>Makurdi</option><option>Obi</option><option>Ogbadibo</option><option>Oju</option><option>Okpokwu</option><option>Ohimini</option><option>Oturkpo</option><option>Tarka</option><option>Ukum</option><option>Ushongo</option>';
	}
	else if (a=='Borno') {
		state='<option disabled selected>Select L.G.A</option><option>Abadam</option><option>Askira/Uba</option><option>Bama</option><option>Bayo</option><option>Biu</option><option>Chibok</option><option>Damboa</option><option>Dikwa</option><option>Gubio</option><option>Guzamala</option><option>Gwoza</option><option>Hawul</option><option>Jere</option><option>Kaga</option><option>Kala/Balge</option><option>Konduga</option><option>Kukawa</option><option>Kwaya Kusar</option><option>Mafa</option><option>Magumeri</option><option>Maiduguri</option><option>Marte</option><option>Mobbar</option><option>Monguno</option><option>Ngala</option><option>Nganzai</option><option>Shani</option>';
}
	else if (a=='Cross-river') {
		state='<option disabled selected>Select L.G.A</option><option>Abi</option><option>Akamkpa</option><option>Akpabuyo</option><option>Bakassi</option><option>Bekwarra</option><option>Biase</option><option>Boki</option><option>Calabar Municipal</option><option>Calabar South</option><option>Etung</option><option>Ikom</option><option>Obanliku</option><option>Obubra</option><option>Obudu</option><option>Odukpani</option><option>Ogoja</option><option>Yakuur</option><option>Yala</option>';
	}
	else if (a=='Delta') {
		state='<option disabled selected>Select L.G.A</option><option>Aniocha North</option><option>Aniocha South</option><option>Bomadi</option><option>Burutu</option><option>Ethiope West</option><option>Ethiope East</option><option>Ika South</option><option>Ika North-East</option><option>Isoko south</option><option>Isoko North</option><option>Ndokwa West</option><option>Ndokwa East</option><option>Okpe</option><option>Oshimili South</option><option>Oshimili North</option><option>Patani</option><option>Sapele</option><option>Udu</option><option>Ughelli South</option><option>Ughelli North</option><option>Ukwani</option><option>Uvwie</option><option>Warri North</option><option>Warri South</option><option>Warri Central</option>';
	}
	else if (a=='Ebonyi') {
		state='<option disabled selected>Select L.G.A</option><option>Abakaliki</option><option>Afikpo South</option><option>Afikpo North</option><option>Ezza North</option><option>Ezza South</option><option>Ebonyi</option><option>Ivo</option><option>Ishielu</option><option>lkwo</option><option>Onicha</option><option>Ohaozara</option><option>Ohaukwu</option>';
	}
	else if (a=='Edo') {
		state='<option disabled selected>Select L.G.A</option><option>Akoko-Edo</option><option>Egor</option><option>Esan Central</option><option>Esan North-East</option><option>Esan South-East</option><option>Esan West</option><option>Etsako Central</option><option>Etsako East</option><option>Etsako West</option><option>Igueben</option><option>Ikpoba-Okha</option><option>Oredo</option><option>Orhionmwon</option><option>Ovia North-East</option><option>Ovia South-West</option><option>Owan East</option><option>Owan West</option><option>Uhunmwonde</option>';
	}
	else if (a=='Ekiti') {
		state='<option disabled selected>Select L.G.A</option><option>Ado</option><option>Efon</option><option>Ekiti-East</option><option>Ekiti-West</option><option>Emure/Ise/Orun</option><option>Ekiti South-West</option><option>Gbonyin</option><option>Ikare</option><option>Irepodun</option><option>Ijero</option><option>Ido/Osi</option><option>Ise/Orun</option><option>Ilejemeje</option><option>Ikole</option><option>Moba</option><option>Oye</option>';
	}
	else if (a=='Enugu') {
		state='<option disabled selected>Select L.G.A</option><option>Aninri</option><option>Enugu East</option><option>Enugu North</option><option>Enugu South</option><option>Ezeagu</option><option>Igbo-Eze North</option><option>Igbo-Eze South</option><option>Igbo-Ekiti</option><option>Isi-Uzo</option><option>Nkanu East</option><option>Nsukka</option><option>Nkanu</option><option>Oji-River</option><option>Udi Agwu</option><option>Uzo-Uwani</option><option>Udenu</option>';
	}
	else if (a=='Gombe') {
		state='<option disabled selected>Select L.G.A</option><option>Akko</option><option>Balanga</option><option>Billiri</option><option>Dukku</option><option>Kaltungo</option><option>Kwami</option><option>Shomgom</option><option>Funakaye</option><option>Gombe</option><option>Nafada/Bajoga</option><option>Yamaltu/Delta</option>';
	}
	else if (a=='Imo') {
		state='<option disabled selected>Select L.G.A</option><option>Aboh Mbaise</option><option>Ahiazu Mbaise</option><option>Ehime Mbano</option><option>Ezinihitte Mbaise</option><option>Ideato North</option><option>Ideato South</option><option>Ihitte/Uboma</option><option>Ikeduru</option><option>Isiala Mbano</option><option>Isu</option><option>Mbaitoli</option><option>Ngor Okpala</option><option>Njaba</option><option>Nkwerre</option><option>Nwangele</option><option>Obowo</option><option>Oguta</option><option>Ohaji/Egbema</option><option>Okigwe</option><option>Onuimo</option><option>Orlu</option><option>Orsu</option><option>Oru East</option><option>Oru West</option><option>Owerri Municipal</option><option>Owerri North</option><option>Owerri West</option>';
	}
	else if (a=='Kwara') {
		state='<option disabled selected>Select L.G.A</option><option>Asa</option><option>Baruten</option><option>Edu</option><option>Ekiti</option><option>Ifelodun</option><option>Ilorin East</option><option>Ilorin West</option><option>Irepodun</option><option>Isin</option><option>Kaiama</option><option>Moro</option><option>Offa</option><option>Oke-Ero</option><option>Oyun</option><option>Pategi</option>';
	}
	else if (a=='Kano') {
		state='<option disabled selected>Select L.G.A</option><option>Ajingi</option><option>Albasu</option><option>Bagwai</option><option>Bebeji</option><option>Bichi</option><option>Bunkure</option><option>Dala</option><option>Dambatta</option><option>Dawakin Kudu</option><option>Dawakin Tofa</option><option>Doguwa</option><option>Fagge</option><option>Gabasawa</option><option>Garko</option><option>Garum</option><option>Mallam</option><option>Gaya</option><option>Gezawa</option><option>Gwale</option><option>Gwarzo</option><option>Kabo</option><option>Kano Municipal</option><option>Karaye</option><option>Kibiya</option><option>Kiru</option><option>kumbotso</option><option>Kunchi</option><option>Kura</option><option>Madobi</option><option>Makoda</option><option>Minjibir</option><option>Nasarawa</option><option>Rano</option><option>Rimin Gado</option><option>Rogo</option><option>Shanono</option><option>Sumaila</option><option>Takali</option><option>Tarauni</option><option>Tofa</option><option>Tsanyawa</option><option>Tudun Wada</option><option>Ungogo</option><option>Warawa</option><option>Wudil</option>';
	}
	else if (a=='Kastina') {
		state='<option disabled selected>Select L.G.A</option><option>Bakori</option><option>Batagarawa</option><option>Batsari</option><option>Baure</option><option>Bindawa</option><option>Charanchi</option><option>Dandume</option><option>Danja</option><option>Dan Musa</option><option>Daura</option><option>Dutsi</option><option>Dutsin-Ma</option><option>Faskari</option><option>Funtua</option><option>Ingawa</option><option>Jibia</option><option>Kafur</option><option>Kaita</option><option>Kankara</option><option>Kankia</option><option>Katsina</option><option>Kurfi</option><option>Kusada</option><option>Mai’Adua</option><option>Malumfashi</option><option>Mani</option><option>Mashi</option><option>Matazuu</option><option>Musawa</option><option>Rimi</option><option>Sabuwa</option><option>Safana</option><option>Sandamu</option><option>Zango</option>';
	}
	else if (a=='Kaduna') {
		state='<option disabled selected>Select L.G.A</option><option>Birni-Gwari</option><option>Chikun</option><option>Giwa</option><option>Igabi</option><option>Ikara</option><option>Jaba</option><option>Jema-a</option><option>Kachia</option><option>Kaduna North</option><option>Kaduna South</option><option>Kagarko</option><option>Kajuru</option><option>Kaura</option><option>Kauru</option><option>Kubau</option><option>Kudan</option><option>Lere</option><option>Makarfi</option><option>Sabon-Gari</option><option>Sanga</option><option>Soba</option><option>Zango-Kataf</option><option>Zaria</option>';
	}
	else if (a=='Kogi') {
		state='<option disabled selected>Select L.G.A</option><option>Adavi</option><option>Ajaokuta</option><option>Ankpa</option><option>Bassa</option><option>Dekina</option><option>Ibaji</option><option>Idah</option><option>Igalamela-Odolu</option><option>Ijumu</option><option>Kabba/Bunu</option><option>Kogi</option><option>Lokoja</option><option>Mopa-Muro</option><option>Ofu</option><option>Ogori/Mangongo</option><option>Okehi</option><option>Okene</option><option>Olamabolo</option><option>Omala</option><option>Yagba East</option><option>Yagba West</option>';
	}else if (a=='Lagos') {
		state='<option disabled selected>Select L.G.A</option><option>Ageg</option><option>Ajeromi-Ifelodun</option><option>Alimosho</option><option>Amuwo-Odofin</option><option>Apapa</option><option>Badagry</option><option>Epe</option><option>Eti-Osa</option><option>Ibeju/Lekki</option><option>Ifako-Ijaye</option><option>Ikeja</option><option>Ikorodu</option><option>Kosofe</option><option>Lagos Island</option><option>Lagos Mainland</option><option>Mushin</option><option>Ojo</option><option>Oshodi-Isolo</option><option>Shomolu</option><option>Surulere</option>';
	}
	else if (a=='Nassarawa') {
		state='<option disabled selected>Select L.G.A</option><option>Akwanga</option><option>Awe</option><option>Doma</option><option>Karu</option><option>Keana</option><option>Keffi</option><option>Kokona</option><option>Lafia</option><option>Nasarawa</option><option>Nasarawa-Eggon</option><option>Obi</option><option>Toto</option><option>Wamba</option>';
	}
	else if (a=='Niger') {
		state='<option disabled selected>Select L.G.A</option><option>Agaie</option><option>Agwara</option><option>Bida</option><option>Borgu</option><option>Bosso</option><option>Chanchaga</option><option>Edati</option><option>Gbako</option><option>Gurara</option><option>Katcha</option><option>Kontagora</option><option>Lapai</option><option>Lavun</option><option>Magama</option><option>Mariga</option><option>Mashegu</option><option>Mokwa</option><option>Muya</option><option>Pailoro</option><option>Rafi</option><option>Rijau</option><option>Shiroro</option><option>Suleja</option><option>Tafa</option><option>Wushishi</option>';
	}
	else if (a=='Ogun') {
		state='<option disabled selected>Select L.G.A</option><option>Abeokuta North</option><option>Abeokuta South</option><option>Ado Odo Ota</option><option>Ewekoro</option><option>Ifo</option><option>Ijebu North East</option><option>Ijebu North</option><option>Ijebu Ode</option><option>Ijebu-East</option><option>Ikenne</option><option>Imeko Afon</option><option>Ipokia</option><option>Obafemi Owode</option><option>Odeda</option><option>Odogbolu</option><option>Ogun Waterside</option><option>Remo North</option><option>Sagamu</option><option>Yewa North</option><option>Yewa South</option>';
	}
	else if (a=='Ondo') {
		state='<option disabled selected>Select L.G.A</option><option>Akoko North East</option><option>Akoko North West</option><option>Akoko South East</option><option>Akoko South West</option><option>Akure North</option><option>Akure South</option><option>Ese-Odo</option><option>Idanre</option><option>Ifedore</option><option>Ilaje</option><option>Ile-Oluji</option><option>Irele</option><option>Okeigbo</option><option>Odigbo</option><option>Okitipupa</option><option>Ondo East</option><option>Ondo West</option><option>Ose</option><option>Owo</option>';
	}
	else if (a=='Osun') {
		state='<option disabled selected>Select L.G.A</option><option>Aiyedade</option><option>Aiyedire</option><option>Atakumosa East</option><option>Atakumosa West</option><option>Boluwaduro</option><option>Boripe</option><option>Ede North</option><option>Ede South</option><option>Egbedore</option><option>Ejigbo</option><option>Ife Central</option><option>Ife East</option><option>Ife North</option><option>Ife South</option><option>Ifedayo</option><option>Ifelodun</option><option>Ila</option><option>Ilesha East</option><option>Ilesha West</option><option>Irepodun</option><option>Irewole</option><option>Isokan</option><option>Iwo</option><option>Obokun</option><option>Odo-Otin</option><option>Ola-Oluwa</option><option>Olorunda</option><option>Oriade</option><option>Orolu</option><option>Osogbo</option>';
	}
	else if (a=='Oyo') {
		state='<option disabled selected>Select L.G.A</option><option>Afijio</option><option>Akinyele</option><option>Atiba</option><option>Atigbo</option><option>Egbeda</option><option>Ibadan Central</option><option>Ibadan North</option><option>Ibadan North West</option><option>Ibadan South East</option><option>Ibadan South West</option><option>Ibarapa Central</option><option>Ibarapa East</option><option>Ibarapa North</option><option>Ido</option><option>Irepo</option><option>Iseyin</option><option>Itesiwaju</option><option>Iwajowa</option><option>Kajola</option><option>Lagelu Ogbomosho North</option><option>Ogbmosho South</option><option>Ogo Oluwa</option><option>Olorunsogo</option><option>Oluyole</option><option>Ona-Ara</option><option>Orelope</option><option>Ori Ire</option><option>Oyo East</option><option>Oyo West</option><option>Saki East</option><option>Saki West</option><option>Surulere</option>';
	}
	else if (a=='Plateau') {
		state='<option disabled selected>Select L.G.A</option><option>Barikin Ladi</option><option>Bassa</option><option>Bokkos</option><option>Jos East</option><option>Jos North</option><option>Jos South</option><option>Kanam</option><option>Kanke</option><option>Langtang North</option><option>Langtang South</option><option>Mangu</option><option>Mikang</option><option>Pankshin</option><option>Quaan Pan</option><option>Riyom</option><option>Shendam</option><option>Wase</option>';
	}else if (a=='') {
		state='<option disabled selected>Select L.G.A</option><option></option>';
	}
	else if (a=='Rivers') {
		state='<option disabled selected>Select L.G.A</option><option>Abua/Odual</option><option>Ahoada East</option><option>Ahoada West</option><option>Akuku Toru</option><option>Andoni</option><option>Asari-Toru</option><option>Bonny</option><option>Degema</option><option>Emohua</option><option>Eleme</option><option>Etche</option><option>Gokana</option><option>Ikwerre</option><option>Khana</option><option>Obia/Akpor</option><option>Ogba/Egbema/Ndoni</option><option>Ogu/Bolo</option><option>Okrika</option><option>Omumma</option><option>Opobo/Nkoro</option><option>Oyigbo</option><option>Port-Harcourt</option><option>Tai</option>';
	}
	else if (a=='Sokoto') {
		state='<option disabled selected>Select L.G.A</option><option>Binji</option><option>Bodinga</option><option>Dange-shnsi</option><option>Gada</option><option>Goronyo</option><option>Gudu</option><option>Gawabawa</option><option>Illela</option><option>Isa</option><option>Kware</option><option>kebbe</option><option>Rabah</option><option>Sabon birni</option><option>Shagari</option><option>Silame</option><option>Sokoto North</option><option>Sokoto South</option><option>Tambuwal</option><option>Tqngaza</option><option>Tureta</option><option>Wamako</option><option>Wurno</option><option>Yabo</option>';
	}
	else if (a=='Taraba') {
		state='<option disabled selected>Select L.G.A</option><option>Ardo-kola</option><option>Bali</option><option>Donga</option><option>Gashaka</option><option>Cassol</option><option>Ibi</option><option>Jalingo</option><option>Karin-Lamido</option><option>Kurmi</option><option>Lau</option><option>Sardauna</option><option>Takum</option><option>Ussa</option><option>Wukari</option><option>Yorro</option><option>Zing</option>';
	}
	else if (a=='Kebbi') {
		state='<option disabled selected>Select L.G.A</option><option>Aleiro</option><option>Arewa-Dandi</option><option>Argungu</option><option>Augie</option><option>Bagudo</option><option>Birnin Kebbi</option><option>Bunza</option><option>Dandi</option><option>Fakai</option><option>Gwandu</option><option>Jega</option><option>Kalgo</option><option>Koko/Besse</option><option>Maiyama</option><option>Ngaski</option><option>Sakaba</option><option>Shanga</option><option>Suru</option><option>Wasagu/Danko</option><option>Yauri</option><option>Zuru</option>';
	}
	else if (a=='Yobe') {
		state='<option disabled selected>Select L.G.A</option><option>Bade</option><option>Bursari</option><option>Damaturu</option><option>Fika</option><option>Fune</option><option>Geidam</option><option>Gujba</option><option>Gulani</option><option>Jakusko</option><option>Karasuwa</option><option>Karawa</option><option>Machina</option><option>Nangere</option><option>Nguru Potiskum</option><option>Tarmua</option><option>Yunusari</option><option>Yusufari</option>';
	}
	else if (a=='Zamfara') {
		state='<option disabled selected>Select L.G.A</option><option>Anka</option><option>Bakura</option><option>Birnin Magaji</option><option>Bukkuyum</option><option>Bungudu</option><option>Gummi</option><option>Gusau</option><option>Kaura</option><option>Namoda</option><option>Maradun</option><option>Maru</option><option>Shinkafi</option><option>Talata Mafara</option><option>Tsafe</option><option>Zurmi</option>';
	}
	else if (a=='F.C.T') {
		state='<option disabled selected>Select L.G.A</option><option>Gwagwalada</option><option>Kuje</option><option>Abaji</option><option>Abuja Municipal</option><option>Bwari</option><option>Kwali</option>';
	}
	document.getElementById("lga").innerHTML = state;
}
