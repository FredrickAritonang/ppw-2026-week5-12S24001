<?php

namespace Database\Seeders;

use App\Models\User;
use App\Support\ProfilContoh;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class PenggunaSeeder extends Seeder
{
    public const PASSWORD = 'PustakaDel2026';

    /** Format tiap baris: awalan|nama tanpa gelar|gelar belakang */
    private const DOSEN = <<<'TXT'
|Adelina Manurung|S.Si, M.Sc.
|Adrianto Prihartantyo|S.Si., M.T.
|Aga Nugraha|S.T., M.T.
|Albert Sagala|S.T., M.T.
|Alfriana Margareta|S.Si., M.Si.
|Alfriska Oktarina Silalahi|S.Pd., M.Si.
|Ana Muliyana|M.Pd.
|Andi Ray Hutauruk|S.T., M.T.
|Andrew Rolas Siagian|S.Pd, M.Pd.
|Apriyanti Sijabat|S.Tr.Kom, M.Kom.
|Ardiles Sinaga|S.T., M.T.
|Arie Satia Dharma|S.T, M.Kom.
|Aryani Sariada Sianipar|S.Si., M.Sc.
|Asido Saragih|S.Pd., M.Sc.
|Basry A Sihotang|S.T., M.T.
|Bella Wahmilyana Asril|S.Si, M.T.
|Berlian Simanjuntak|S.Si, M.T
|Chandro Pardede|S.Kom., M.Sc.
|Chintya Sinar Lumbantoruan|S.T., M.T.
|Cynthia Deborah Nababan|S.Tr.Kom., M.Kom.
Dr.|Arlinta Christy Barus|ST., M.InfoTech.
Dr.|Fitriani Tupa Ronauli Silalahi|S.Si., M.Si.
Dr.|Johannes Harungguan Sianipar|S.T., M.T.
Dr.|Mariana Simanjuntak|M.Sc.
Dr.|Merry Meryam Martgrita|S.Si., M.Si.
Dr.|Rentauli Mariah Silalahi|S.Pd., M.Ed
Dr.|Santi Agustina Manalu|S.S., M.Pd
Dra.|Roga Florida Kembaren|M.Si.
|Eka Nurul Falah|S.T., M.T.
|Eka Stephani Sinambela|S.Si., M.Sc.
|Ellyas Alga Nainggolan|S.T.P., M.Sc., Ph.D.
|FBA Fitriani|S.T., M.T.
|Febrian Cornellius Sidabutar|S.T., M.T.
|Febrian Winston Hutagalung|S.T., M.T.
|Fidelis Haposan Silalahi|SH., MH.
|Flavia Triska Pasaribu|S.Pd.
|Frengki Simatupang|S.T.Kom., M.T.
|Gerry Italiano Wowiling|S.Tr.Kom., M.T.
|Goklas Henry Agus Panjaitan|S.Tr.Kom., M.T.
|Good Fried Panggabean|S.T., MT., Ph.D
|Guntur Petrus Boy Knight|S.T., M.T
|Hadi Sutanto Saragi|S.T, M.Eng
|Herimanto|S.Kom., M.Kom
|Herlina Panggabean|S.H., M.H.
|Hernawati Susanti Samosir|SST., M.Kom.
|Humasak Tommy Argo Simanjuntak|S.T., M.ISD
|Indra Hartarto Tambunan|Ph.D.
Ir.|Josua Boyke William Jawak|ST., M.Ds
|Istas Pratomo Manalu|S.Si., M.Sc.
|Iswanti Sihaloho|S.Si., M.Si
|Ivo Vincenes Sibarani|S.T., M.Sc.
|Iwan Jepri|S.Si., M.Pd.
|Jaya Santoso|S.Si., M.Si.
|Juli Yanti Damanik|S.S., M.Tesol.
|Junita Amalia|S.Pd., M.Si
|Lit Malem Ginting|S.Si, MT
|Lucia Kusumawati|S.Si., M.Sc., Ph.D.
|Marojahan MT. Sigiro|ST., M.Sc
|Monalisa Pasaribu|SS., M.Ed (TESOL)
|Muhammad Ilham Maulana|S.T., M.T
|Nathasya Dona Uly Tampubolon|S.T., M.Eng.
|Niko Saripson P. Simamora|S.T., MAB
|Oppir Hutapea|S.Tr.Kom., M.Kom
|Pandapotan Siagian|ST, M.Eng
|Philippians Manurung|S.T., M.T.
|Prima Ria Rumata Panggabean|S.Pd., M.Si.
Prof. Dr.|Arnaldo Marulitua Sinaga|S.T., M.InfoTech.
Prof. Dr. Ir.|Saswinadi Sasmojo|M.Sc, Ph.D
Prof. Dr.|Robert Saragih|MT
Prof. Ir.|Togar M. Simatupang|M.Tech., Ph.D., IPU.
|Ranty Deviana Siahaan|S.Kom., M.Eng.
|Riyanthi Angrainy Sianturi|M.Ds.
|Rizal Horas Manahan Sinaga|S.Si., M.T., Ph.D.
|Romasi Gohanna Sibarani|S.Kom., M.Eng
|Rosni Lumbantoruan|M.ISD., Ph.D.
|Rudy Chandra|S.Kom., M.Kom
|Rumondang Miranda Marsaulina|S.P, M.Si
|Samuel Hasudungan Tampubolon|S.T., MSc.
|Samuel Indra Gunawan Situmeang|S.T.I., M.Sc
|Santi Febri Arianti|S.Si, M.Sc
|Sarah Rosdiana Tambunan|S.Kom., M.Cs.
|Sari Muthia Silalahi|S.Pd., M.Ed
|Susi Eva Maria Purba|S.Kom., M.Cs.
|Tegar Arifin Prasetyo|S.Si., M.Si.
|Tennov Simanjuntak|S.T, M.Sc
|Theodora M Putri|S.T., M.Sc.
|Togu Novriansyah Turnip|S.S.T., M.I.M
|Wesly Mailander Siagian|S.Pt., M.M.
|Yaya Setiyadi|S.Si., MT
|Yohanssen Pratama|S.Si, M.T
|Yulisa Lestari|S.Si, M.T
|Deni Parlindungan Lumbantoruan|ST., M.Eng., Ph.D.
|Abhishek Chadha|
|Ahmad Fauzi|S.Si., M.T.I.
|Ahmad Zatnika Purwalaksana|S.Si., M.Si.
|Alexander Lumbantobing|S.TrKom
|Anastasia L. Rajagukguk|
|Andy Trirakhmadi|S.T., M.T
|Anita Emi Kurniawati|S.Pd, M.Si
|Astiti Aditia|ST., M.T.
|Asyraf Attharig Putra G|S.Pd., M.T.
|B. Junedi Hutagaol|S.Kom., M.M.S.I.
|Benedikta Anna Haulian Siboro|S.T., M.Sc.
|Bony Parulian Josaphat Marbun|
|Candra Taufik|
|Canggih Gultom|S.Kom
|Charla Tri Selda Manik|ST., M.Eng
|Christin Erniati Panjaitan|ST., M.Sc
|Christoper Janwar Saputra Sinaga|S.T., M.A.B.
|Corry Elsa Noviyanti|S.Kom
|Danang Junaedi|ST, MT
|Devis Wawan Saputra|ST., MBA.
|Dian Ira Putri Hutasoit|S.Tr.Kom
|Doni Arzinal|
Dr.|Batara Parada Siahaan|S.Kom., M.S.M., M.I.M
Dr. Ir.|Bambang S.P Abednego|
Dr.|Maslina Simanjuntak|S.Pd., M.Pd.
Dr.|Sony Adhi Susanto|S.Si, M.Sc
Dr.|Yosef Barita Sar Manik|S.T., M.Sc.
Drs.|Tahan Hutahaean|M.Ed
|Ecko Fernando Basarah Manalu|
|Elisa Margareth Sibarani|ST, M.ISD
|Ellida Damanik|
|Ernie Bertha Nababan|S.Pd, M.Pd
|Ezwar Roezzaman|S.Si
|Fica Aida Nadhifatul Aini|S.ST., M.Sc
|Frederik Bungaran Ishak Situmeang|M.Sc
|Fredy Sibarani|
|Gandhi Maruli Tua Manalu|
|Geoffrey Moss|
|Georgeanna Victoria Shores|
|Graham Neil Hornby|
|Henry Edison Sitorus|
|Herman Santoso Pakpahan|S.Si., M.PFis
Hj.|Latifah Sudarmy|S.Ag.
|I Gde Eka Dirgayussa|S.Pd, M.Si
|Ike Fitriyaningsih|S.Si., M.Si
|Imelda R. Simanjuntak|ST, M.Sc
|Immanuel Panjaitan|S.Kom
|Indra Siregar|
|Indra Siregar|S.T, M.B.A
|Inte Christinawati Bu'ulolo|ST., M.T.I
|Iustisia Natalia Simbolon|S.Kom., M.T.
|James Benjamin Callen Lewis|
|Juli Loisiana Butarbutar|
|Kisno Shinoda|
|Kisno|S.Pd, M.Pd
|Kristina Gloria Simanjuntak|S.Sos
|Kurnia Djaja|
|Leonard (Lenny) M. Shores|
|Liu Wei|
|M. Jusly Penus Sagala|SH., MS.
|M. Yusuf Hakim Widianto|M.Si, M.Sc.
|Maisevli Harika|M.T, M.Eng.
|Maria Emmanuelly Siahaan|
|Mariani Simatupang|SPd
|Marselo Hamonangan Sitorus|
|Mauritz Panggabean|
|Monita Pasaribu|S.Si., MT.
|Mukhammad Solikhin|S.Si, M.Si
|Musthofa Lutfi|
|Nancy Margried|B.Sc., M.Sc
|Nenni Mona Aruan|S.Pd., M.Si
|Nina Juliana Roberta Turnip|S.T., M.T
|Novalina Hutabarat|A.Md
|Novalina Hutabarat|S.Kom., M.T.I
|Olnes Yosefa Hutajulu|S.Pd., M.Eng
|Ondy Dharma Indra Sukma|
|Pandapotan Napitupulu|S.T, M.T
|Rahul Kaushik|
|Ramot Lubis|
|Regina Ayunita Tarigan|S.Si., M.Sc
|Ricardo Chandra Situmeang|S.Psi, M.A
|Rien Rakhmana|S.Si, M.T
|Rina Sibuea|
|Rini Juliana Sipahutar|S.Tr.Kom., M.Kom.
|Roberto|
|Rotua Cyntia Dame Panjaitan|S.Kom, M.Pd
|Rusyadi|PAI., M.Pd.
|Sahat Pandapotan Nainggolan|S.Mat., M.PMat
|Tahan HJ Silhombing|S.Pd., M. App Ling (TESOL)
|Teamsar Muliadi Panggabean|S.Kom, PGCert
|Timbang Pangaribuan|
|Tulus Pardamean Simanjuntak|SST
|Vidya Vrat Agarwal|
|Wiwin Sry Adinda Banjarnahor|S.Kom, M.Sc
|Yohanes Polin Bakara|S.T.
|Yoli Agnesia|S.Pd., M.Si.
|Yuniarta Basani|S.Si, M.Si
|Zhou Xin|
|Bonar Lumban Tobing|
Dr.|Inggriani Liem|
|Edwin Swandi Sijabat|SST
|Eka Trisno Samosir|S.Kom, PGDip
|Rismal|S.Pd., M.Si
Ir.|Partumpuan Naiborhu|M.M
|Anthon Roberto Tampubolon|S.Kom., M.T.
|Dedy Anwar|ST., MT
|Mario E. S. Simaremare|S.Kom., M.Sc.
|Meiyer Marthen Kinda|S.T.M.T
|Parmonangan R. Togatorop|S.Kom., M.T.I
|Roy Deddy Hasiholan Lumban Tobing|S.T., M.ICT
|Verawaty Situmorang|S.Kom., M.T.I
TXT;

    public function run(): void
    {
        Hash::setRounds(4); // agar seeding ratusan akun cepat (hanya untuk demo)

        $csv = ['username,password,peran,nama'];
        $dipakai = [];

        $buat = function (string $nama, string $username, string $role) use (&$csv, &$dipakai) {
            $dasar = $username;
            $n = 2;
            while (isset($dipakai[$username])) {
                $username = $dasar.$n++;
            }
            $dipakai[$username] = true;

            User::create([
                'name' => $nama,
                'username' => $username,
                'email' => $username.($role === 'mahasiswa' ? '@students.del.ac.id' : '@del.ac.id'),
                'role' => $role,
                'password' => self::PASSWORD,
            ] + ProfilContoh::untuk($username, $role, $nama));

            $csv[] = '"'.$username.'","'.self::PASSWORD.'","'.$role.'","'.str_replace('"', '', $nama).'"';
        };

        // Mahasiswa dari database/data/mahasiswa.csv (NIM 12S24xxx -> username iss24xxx)
        $baris = file(database_path('data/mahasiswa.csv'), FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach (array_slice($baris, 1) as $b) {
            [$nim, $nama] = explode(',', $b, 2);
            $buat(trim($nama), 'iss'.substr(trim($nim), 3), 'mahasiswa');
        }

        // Dosen
        foreach (explode("\n", self::DOSEN) as $baris) {
            $baris = trim($baris);
            if ($baris === '') {
                continue;
            }
            [$awalan, $nama, $gelar] = array_pad(explode('|', $baris), 3, '');
            $lengkap = trim($awalan.' '.$nama).($gelar !== '' ? ', '.$gelar : '');
            $buat($lengkap, Str::slug($nama, ''), 'dosen');
        }

        // Pegawai perpustakaan
        foreach ([
            ['Jesicha Ulina Hutabarat', 'S.Sos'],
            ['Enjelina Limbong', 'S.S.I'],
            ['Yulia Sari Tripena', 'S.S.I'],
            ['Tiurma Lumban Gaol', 'SP., M.P.'],
        ] as [$nama, $gelar]) {
            $buat($nama.', '.$gelar, Str::slug($nama, ''), 'pustakawan');
        }

        // Staf akademik
        $buat('Staf Akademik 1', 'stafakademik1', 'staf_akademik');
        $buat('Staf Akademik 2', 'stafakademik2', 'staf_akademik');

        file_put_contents(storage_path('app/akun-demo.csv'), implode("\n", $csv)."\n");
    }
}