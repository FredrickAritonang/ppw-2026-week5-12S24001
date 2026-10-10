<?php

namespace Database\Seeders;

use App\Models\Buku;
use App\Models\Kategori;
use App\Models\Prodi;
use Illuminate\Database\Seeder;

class EbookSeeder extends Seeder
{
    /**
     * 100 e-book terbuka (open access) yang dapat dibaca online lewat file_url.
     * Sumber daftar: EbookFoundation/free-programming-books.
     * Penerbit diisi dengan situs sumber, tahun terbit diisi tahun katalog (2025)
     * karena data tahun asli tidak ada di daftar sumber.
     */
    public function run(): void
    {
        $kat = Kategori::pluck('id', 'nama_kategori')->all();
        $prodi = Prodi::pluck('id', 'kode')->all();

        // [isbn, judul, penulis, penerbit (situs sumber), kategori, topik, file_url]
        $ebooks = [
            ['EB-001', 'Arduino-docs : A beginner\'s guide', 'Paramesh Chandra', 'arduino-doc.readthedocs.io', 'Pemrograman', 'Arduino', 'https://arduino-doc.readthedocs.io/en/latest/'],
            ['EB-002', 'Arduino Programming Notebook', 'Brian Evans', 'unglue.it', 'Pemrograman', 'Arduino', 'https://unglue.it/work/152452'],
            ['EB-003', 'Arduino Projects Book', 'Scott Fitzgerald and Michael Shiloh', 'eitkw.com', 'Pemrograman', 'Arduino', 'https://www.eitkw.com/wp-content/uploads/2020/03/Arduino_Projects_Book.pdf'],
            ['EB-004', 'A fundamental introduction to x86 assembly programming', 'Project Nayuki', 'nayuki.io', 'Pemrograman', 'Assembly Language', 'https://www.nayuki.io/page/a-fundamental-introduction-to-x86-assembly-programming'],
            ['EB-005', 'Assemblers And Loaders (1993)', 'David Salomon', 'davidsalomon.name', 'Pemrograman', 'Assembly Language', 'https://www.davidsalomon.name/assem.advertis/asl.pdf'],
            ['EB-006', 'Advanced Bash-Scripting Guide', 'M. Cooper', 'tldp.org', 'Sistem Operasi', 'Bash', 'https://tldp.org/LDP/abs/html/'],
            ['EB-007', 'Bash Guide for Beginners (2008)', 'M. Garrels', 'tldp.org', 'Sistem Operasi', 'Bash', 'https://www.tldp.org/LDP/Bash-Beginners-Guide/html/'],
            ['EB-008', 'BASH Programming (2000)', 'Mike G.', 'tldp.org', 'Sistem Operasi', 'Bash', 'https://tldp.org/HOWTO/Bash-Prog-Intro-HOWTO.html'],
            ['EB-009', 'BashGuide', 'Maarten Billemont', 'mywiki.wooledge.org', 'Sistem Operasi', 'Bash', 'https://mywiki.wooledge.org/BashGuide'],
            ['EB-010', 'Algorithms Design (in C)', 'Paulo Feofiloff', 'ime.usp.br', 'Pemrograman', 'C', 'https://www.ime.usp.br/~pf/algorithms/'],
            ['EB-011', 'Beej\'s Guide to C Programming', 'Brian "Beej Jorgensen" Hall', 'beej.us', 'Pemrograman', 'C', 'https://beej.us/guide/bgc/'],
            ['EB-012', 'Beej\'s Guide to the GNU Debugger (GDB)', 'Brian "Beej Jorgensen" Hall', 'beej.us', 'Pemrograman', 'C', 'https://beej.us/guide/bggdb/'],
            ['EB-013', 'Build Your Own Redis with C/C++', 'build-your-own.org', 'build-your-own.org', 'Pemrograman', 'C', 'https://build-your-own.org'],
            ['EB-014', 'C Handbook', 'Flavio Copes', 'thevalleyofcode.com', 'Pemrograman', 'C', 'https://thevalleyofcode.com/c/'],
            ['EB-015', 'C Internals', 'Rajeev Kumar', 'avabodh.com', 'Pemrograman', 'C', 'https://www.avabodh.com/cin/cin.html'],
            ['EB-016', 'C# Features Succinctly', 'Dirk Strauss', 'syncfusion.com', 'Pemrograman', 'C#', 'https://www.syncfusion.com/succinctly-free-ebooks/c-sharp-features-succinctly'],
            ['EB-017', 'C# Programming Yellow Book', 'Rob Miles', 'robmiles.com', 'Pemrograman', 'C#', 'https://www.robmiles.com/s/CSharp-Book-2019-Refresh.pdf'],
            ['EB-018', 'C# Smorgasbord', 'Filip Ekberg', 'filipekberg.se', 'Pemrograman', 'C#', 'https://www.filipekberg.se'],
            ['EB-019', 'A Modular Structured Approach using C++', 'Kenneth Leroy Busbee', 'open.umn.edu', 'Pemrograman', 'C++', 'https://open.umn.edu/opentextbooks/textbooks/144'],
            ['EB-020', 'An Introduction to the USA Computing Olympiad, C++ Edition', 'Darren Yao', 'darrenyao.com', 'Pemrograman', 'C++', 'https://darrenyao.com/usacobook/cpp.pdf'],
            ['EB-021', 'C++ Annotations', 'Frank B. Brokken', 'fbb-git.gitlab.io', 'Pemrograman', 'C++', 'https://fbb-git.gitlab.io/cppannotations/'],
            ['EB-022', 'C++ Coding Standard', 'Todd Hoff', 'possibility.com', 'Pemrograman', 'C++', 'https://possibility.com/Cpp/CppCodingStandard.html'],
            ['EB-023', 'C++ For Programmers', 'JT Kalnay', 'tfetimes.com', 'Pemrograman', 'C++', 'https://tfetimes.com/wp-content/uploads/2015/04/c-for-c-programmers.pdf'],
            ['EB-024', 'Hands-on Elixir & OTP: Cryptocurrency trading bot', 'Kamil Skowron', 'book.elixircryptobot.com', 'Pemrograman', 'Elixir', 'https://book.elixircryptobot.com'],
            ['EB-025', 'Joy of Elixir', 'Ryan Bigg', 'joyofelixir.com', 'Pemrograman', 'Elixir', 'https://joyofelixir.com'],
            ['EB-026', 'Essentials of Go Programming', 'Baiju Muthukadan', 'essentials-of-go-programming.readthedocs.io', 'Pemrograman', 'Go', 'https://essentials-of-go-programming.readthedocs.io'],
            ['EB-027', 'Go for Python Programmers', 'Jason McVetta', 'golang-for-python-programmers.readthedocs.io', 'Pemrograman', 'Go', 'https://golang-for-python-programmers.readthedocs.io/en/latest'],
            ['EB-028', 'Go Handbook', 'Flavio Copes', 'thevalleyofcode.com', 'Pemrograman', 'Go', 'https://thevalleyofcode.com/go/'],
            ['EB-029', 'Go Succinctly', 'Mark Lewin', 'syncfusion.com', 'Pemrograman', 'Go', 'https://www.syncfusion.com/succinctly-free-ebooks/go-succinctly'],
            ['EB-030', 'The Haskell Road to Logic, Math and Programming', 'Kees Doets, Jan van Eijck', 'fldit-www.cs.tu-dortmund.de', 'Pemrograman', 'Haskell', 'https://fldit-www.cs.tu-dortmund.de/~peter/PS07/HR.pdf'],
            ['EB-031', 'The Haskell School of Music - From Signals to Symphonies', 'Paul Hudak', 'cs.yale.edu', 'Pemrograman', 'Haskell', 'https://www.cs.yale.edu/homes/hudak/Papers/HSoM.pdf'],
            ['EB-032', 'CSS Tutorial; The Complete Modern Guide', 'Swapnil Raja', 'html5andcss3.org', 'Pengembangan Web', 'HTML and CSS', 'https://html5andcss3.org/css-tutorial-pdf/'],
            ['EB-033', 'Enduring CSS', 'Ben Frain', 'ecss.benfrain.com', 'Pengembangan Web', 'HTML and CSS', 'https://ecss.benfrain.com/preface.html'],
            ['EB-034', 'How To Build a Website with HTML', 'Erin Glass', 'digitalocean.com', 'Pengembangan Web', 'HTML and CSS', 'https://www.digitalocean.com/community/books/how-to-build-a-website-with-html-ebook'],
            ['EB-035', 'HTML Tutorial; The Complete Practical Guide', 'Swapnil Raja', 'html5andcss3.org', 'Pengembangan Web', 'HTML and CSS', 'https://html5andcss3.org/html-tutorial-pdf/'],
            ['EB-036', 'HTML5 Quick Learning Guide', 'HTML5Templates', 'ossblog.org', 'Pengembangan Web', 'HTML and CSS', 'https://www.ossblog.org/wp-content/uploads/2017/06/html5-quick-learning-quide.pdf'],
            ['EB-037', 'HTML5 Shoot \'em Up in an Afternoon', 'Bryan Bibat', 'leanpub.com', 'Pengembangan Web', 'HTML and CSS', 'https://leanpub.com/html5shootemupinanafternoon/read'],
            ['EB-038', 'A Practical Introduction to Data Structures and Algorithm Analysis Third Edition (Java Version)', 'Clifford A. Shaffer', 'people.cs.vt.edu', 'Pemrograman', 'Java', 'https://people.cs.vt.edu/shaffer/Book/Java3e20100119.pdf'],
            ['EB-039', 'An Introduction to the USA Computing Olympiad, Java Edition', 'Darren Yao', 'darrenyao.com', 'Pemrograman', 'Java', 'https://darrenyao.com/usacobook/java.pdf'],
            ['EB-040', 'Building Back-End Web Apps with Java, JPA and JSF', 'Mircea Diaconescu, Gerd Wagner', 'web-engineering.info', 'Pemrograman', 'Java', 'https://web-engineering.info/tech/JavaJpaJsf/book/'],
            ['EB-041', 'Introduction to Computer Science "booksite"', 'Robert Sedgewick, Kevin Wayne', 'introcs.cs.princeton.edu', 'Pemrograman', 'Java', 'https://introcs.cs.princeton.edu/java/cs/'],
            ['EB-042', 'Introduction to Programming Using Java (5th Edition - final version, 2010 Jun)', 'David J. Eck', 'math.hws.edu', 'Pemrograman', 'Java', 'https://math.hws.edu/eck/cs124/javanotes5'],
            ['EB-043', 'Java Application Development on Linux (2005)', 'Carl Albing, Michael Schwarz', 'ptgmedia.pearsoncmg.com', 'Pemrograman', 'Java', 'https://ptgmedia.pearsoncmg.com/images/013143697X/downloads/013143697X_book.pdf'],
            ['EB-044', 'Airbnb JavaScript Style Guide', 'Airbnb', 'airbnb.io', 'Pengembangan Web', 'JavaScript', 'https://airbnb.io/javascript/'],
            ['EB-045', 'Bible of JS', 'Harsh Sharma, Sheryians Coding School', 'sheryians.com', 'Pengembangan Web', 'JavaScript', 'https://sheryians.com/download/bibleofjs_by_sheryians'],
            ['EB-046', 'Building Front-End Web Apps with Plain JavaScript', 'Gerd Wagner', 'web-engineering.info', 'Pengembangan Web', 'JavaScript', 'https://web-engineering.info/JsFrontendApp-Book'],
            ['EB-047', 'Deep JavaScript: Theory and techniques', 'Axel Rauschmayer', 'exploringjs.com', 'Pengembangan Web', 'JavaScript', 'https://exploringjs.com/deep-js'],
            ['EB-048', 'Designing Scalable JavaScript Applications', 'Emmit Scott', 'manning.com', 'Pengembangan Web', 'JavaScript', 'https://www.manning.com/books/designing-scalable-javascript-applications'],
            ['EB-049', 'Dev Docs', 'Various', 'devdocs.io', 'Pengembangan Web', 'JavaScript', 'https://devdocs.io/javascript/'],
            ['EB-050', 'DOM Enlightenment', 'Cody Lindley', 'frontendmasters.com', 'Pengembangan Web', 'JavaScript', 'https://frontendmasters.com/guides/javascript-enlightenment/'],
            ['EB-051', 'Google JavaScript Style Guide', 'Aaron Whyte dkk.', 'google.github.io', 'Pengembangan Web', 'JavaScript', 'https://google.github.io/styleguide/javascriptguide.xml'],
            ['EB-052', 'JavaScript for Impatient Programmers (ES2022 edition)', 'Axel Rauschmayer', 'exploringjs.com', 'Pengembangan Web', 'JavaScript', 'https://exploringjs.com/impatient-js/'],
            ['EB-053', 'JavaScript Allongé', 'Reginald Braithwaite', 'leanpub.com', 'Pengembangan Web', 'JavaScript', 'https://leanpub.com/javascript-allonge/read'],
            ['EB-054', 'Quantitative Economics with Julia', 'Jesse Perla, Thomas J. Sargent, John Stachurski', 'julia.quantecon.org', 'Pemrograman', 'Julia', 'https://julia.quantecon.org'],
            ['EB-055', 'Advanced Linux :The Linux Shell And Toolkit', 'Tobias Elsner, Anselm Lingnau', 'tuxcademy.org', 'Sistem Operasi', 'Linux', 'https://www.tuxcademy.org/download/en/grd2/grd2-en-manual.pdf'],
            ['EB-056', 'Introduction to Linux - A Hands on Guide', 'Machtelt Garrels', 'tldp.org', 'Sistem Operasi', 'Linux', 'https://tldp.org/LDP/intro-linux/intro-linux.pdf'],
            ['EB-057', 'Linux Administration II : Linux as a Network Client', 'Anselm Lingnau, Tobias Elsner', 'tuxcademy.org', 'Sistem Operasi', 'Linux', 'https://www.tuxcademy.org/download/en/adm2/adm2-en-manual.pdf'],
            ['EB-058', 'Linux Essentials', 'Linux Professional Institute', 'learning.lpi.org', 'Sistem Operasi', 'Linux', 'https://learning.lpi.org/en/learning-materials/010-160/'],
            ['EB-059', 'Linux Security', 'Paul Cobbaut', 'linux-training.be', 'Sistem Operasi', 'Linux', 'https://linux-training.be/linuxsec.pdf'],
            ['EB-060', 'Lisp Hackers: Interviews with 100x More Productive Programmers', 'Vsevolod Dyomkin', 'leanpub.com', 'Pemrograman', 'Lisp', 'https://leanpub.com/lisphackers/read'],
            ['EB-061', 'Loving Common Lisp, or the Savvy Programmer\'s Secret Weapon', 'Mark Watson', 'leanpub.com', 'Pemrograman', 'Lisp', 'https://leanpub.com/lovinglisp/read'],
            ['EB-062', 'Emacs Lisp Elements', 'Protesilaos Stavrou', 'protesilaos.com', 'Pemrograman', 'Lisp', 'https://protesilaos.com/emacs/emacs-lisp-elements'],
            ['EB-063', 'PHP Handbook', 'Flavio Copes', 'thevalleyofcode.com', 'Pengembangan Web', 'PHP', 'https://thevalleyofcode.com/php/'],
            ['EB-064', 'PHP Reference: Beginner to Intermediate PHP5', 'Mario Lurig', 'phpreferencebook.com', 'Pengembangan Web', 'PHP', 'https://phpreferencebook.com/pdf/download/'],
            ['EB-065', '100 (and counting) Laravel Quick Tips', 'Povilas Korop / LaravelDaily Team', 'laraveldaily.com', 'Pengembangan Web', 'PHP', 'https://laraveldaily.com/wp-content/uploads/2020/04/laravel-tips-2020-04.pdf'],
            ['EB-066', 'Learning Laravel', 'Compiled from StackOverflow Documentation', 'riptutorial.com', 'Pengembangan Web', 'PHP', 'https://riptutorial.com/Download/laravel.pdf'],
            ['EB-067', 'A Practical Introduction to Python Programming', 'Brian Heinold', 'brianheinold.net', 'Pemrograman', 'Python', 'https://www.brianheinold.net/python/'],
            ['EB-068', 'An Introduction to Statistical Learning with Applications in Python', 'Gareth James dkk.', 'statlearning.com', 'Pemrograman', 'Python', 'https://www.statlearning.com'],
            ['EB-069', 'Architecture Patterns with Python', 'Harry J.W. Percival, Bob Gregory', 'cosmicpython.com', 'Pemrograman', 'Python', 'https://www.cosmicpython.com/book/preface.html'],
            ['EB-070', 'Clean Architectures in Python - A practical approach to better software design (2022)', 'Leonardo Giordani', 'thedigitalcatbooks.com', 'Pemrograman', 'Python', 'https://www.thedigitalcatbooks.com/pycabook-introduction/'],
            ['EB-071', 'Computational and Inferential Thinking', 'Ani Adhikari, John DeNero, David Wagner', 'inferentialthinking.com', 'Pemrograman', 'Python', 'https://inferentialthinking.com/chapters/intro.html'],
            ['EB-072', 'Geographic Data Science with Python', 'Sergio Rey, Dani Arribas-Bel, Levi John Wolf', 'geographicdata.science', 'Pemrograman', 'Python', 'https://geographicdata.science/book/intro.html'],
            ['EB-073', 'Hands-on Python 3 Tutorial', 'Andrew N. Harrington', 'anh.cs.luc.edu', 'Pemrograman', 'Python', 'https://anh.cs.luc.edu/handsonPythonTutorial'],
            ['EB-074', 'How to Code in Python 3', 'Lisa Tagliaferri', 'assets.digitalocean.com', 'Pemrograman', 'Python', 'https://assets.digitalocean.com/books/python/how-to-code-in-python.pdf'],
            ['EB-075', 'How to Think Like a Computer Scientist: Learning with Python 1st Edition', 'Allen B. Downey, Jeff Elkner, Chris Meyers', 'greenteapress.com', 'Pemrograman', 'Python', 'https://greenteapress.com/wp/learning-with-python/'],
            ['EB-076', 'An Introduction to Statistical Learning with Applications in R', 'Gareth James dkk.', 'stat.berkeley.edu', 'Pemrograman', 'R', 'https://www.stat.berkeley.edu/~rabbee/s154/ISLR_First_Printing.pdf'],
            ['EB-077', 'Behavior Analysis with Machine Learning Using R', 'Enrique Garcia Ceja', 'enriquegit.github.io', 'Pemrograman', 'R', 'https://enriquegit.github.io/behavior-free/'],
            ['EB-078', 'Data Visualization with R', 'Martin Schweinberger', 'ladal.edu.au', 'Pemrograman', 'R', 'https://ladal.edu.au/dviz.html'],
            ['EB-079', 'Deep R Programming', 'Marek Gagolewski', 'arxiv.org', 'Pemrograman', 'R', 'https://arxiv.org/abs/2301.01188'],
            ['EB-080', 'Core Ruby Tools', 'Launch School', 'launchschool.com', 'Pemrograman', 'Ruby', 'https://launchschool.com/books/core_ruby_tools'],
            ['EB-081', 'Developing Games With Ruby', 'Tomas Varaneckas', 'leanpub.com', 'Pemrograman', 'Ruby', 'https://leanpub.com/developing-games-with-ruby/read'],
            ['EB-082', 'Ruby Web Dev: The Other Way', 'Yevhen Kuzminov', 'leanpub.com', 'Pemrograman', 'Ruby', 'https://leanpub.com/rwdtow/read'],
            ['EB-083', 'Kestrels, Quirky Birds, and Hopeless Egocentricity', 'Reg Braithwaite', 'leanpub.com', 'Pemrograman', 'Ruby', 'https://leanpub.com/combinators/read'],
            ['EB-084', 'A Gentle Introduction To Rust', 'Steve J Donovan', 'stevedonovan.github.io', 'Pemrograman', 'Rust', 'https://stevedonovan.github.io/rust-gentle-intro'],
            ['EB-085', 'Easy Rust', 'David McLeod', 'dhghomon.github.io', 'Pemrograman', 'Rust', 'https://dhghomon.github.io/easy_rust/'],
            ['EB-086', 'Effective Rust', 'David Drysdale', 'lurklurk.org', 'Pemrograman', 'Rust', 'https://www.lurklurk.org/effective-rust'],
            ['EB-087', 'High Assurance Rust', 'Tiemoko Ballo', 'highassurance.rs', 'Pemrograman', 'Rust', 'https://highassurance.rs'],
            ['EB-088', 'Functional Programming for Mortals', 'Sam Halliday', 'leanpub.com', 'Pemrograman', 'Scala', 'https://leanpub.com/fpmortals/read'],
            ['EB-089', 'Functional Programming, Simplified (Scala edition)', 'Alvin Alexander', 'alvinalexander.com', 'Pemrograman', 'Scala', 'https://alvinalexander.com/photos/functional-programming-simplied-free-pdf-preview'],
            ['EB-090', 'Hello, Scala', 'Alvin Alexander', 'alvinalexander.com', 'Pemrograman', 'Scala', 'https://alvinalexander.com/photos/hello-scala-free-pdf-preview'],
            ['EB-091', 'Developing Time-Oriented Database Applications in SQL', 'Richard T. Snodgrass', 'www2.cs.arizona.edu', 'Basis Data', 'SQL (implementation agnostic)', 'https://www2.cs.arizona.edu/~rts/tdbbook.pdf'],
            ['EB-092', 'How to Design Structured Database Systems Using SQL', 'Daniel García Solla, freeCodeCamp', 'freecodecamp.org', 'Basis Data', 'SQL (implementation agnostic)', 'https://www.freecodecamp.org/news/how-to-design-structured-database-systems-using-sql-full-book/'],
            ['EB-093', 'Learning SQL', 'Compiled from StackOverflow Documentation', 'riptutorial.com', 'Basis Data', 'SQL (implementation agnostic)', 'https://riptutorial.com/Download/sql.pdf'],
            ['EB-094', 'Oracle8i Concepts: Chapter 15 - SQL and PL/SQL', 'Lefty Leverenz, Diana Rehfield, Cathy Baird', 'docs.oracle.com', 'Basis Data', 'SQL (implementation agnostic)', 'https://docs.oracle.com/cd/A87860_01/doc/server.817/a76965/c14sqlpl.htm#5943'],
            ['EB-095', 'SQL Queries Succinctly', 'Nick Harrison', 'syncfusion.com', 'Basis Data', 'SQL (implementation agnostic)', 'https://www.syncfusion.com/ebooks/sql_queries_succinctly'],
            ['EB-096', 'Tackling TypeScript: Upgrading from JavaScript', 'Axel Rauschmayer', 'exploringjs.com', 'Pengembangan Web', 'TypeScript', 'https://exploringjs.com/tackling-ts/toc.html'],
            ['EB-097', 'TypeScript Accelerated', 'James Porter', 'accelerated.amimetic.co.uk', 'Pengembangan Web', 'TypeScript', 'https://accelerated.amimetic.co.uk'],
            ['EB-098', 'TypeScript Handbook', 'Microsoft', 'typescriptlang.org', 'Pengembangan Web', 'TypeScript', 'https://www.typescriptlang.org/docs/handbook/intro.html'],
            ['EB-099', 'TypeScript Handbook for React Developers', 'Yazdun Fadali', 'freecodecamp.org', 'Pengembangan Web', 'TypeScript', 'https://www.freecodecamp.org/news/typescript-tutorial-for-react-developers/'],
            ['EB-100', 'UNIX Commands and Concepts', 'University of Wyoming', 'uwyo.edu', 'Sistem Operasi', 'Unix', 'https://www.uwyo.edu/data-science/_files/docs/unix.pdf'],
        ];

        foreach ($ebooks as [$isbn, $judul, $penulis, $sumber, $kategori, $topik, $url]) {
            $buku = Buku::updateOrCreate(
                ['isbn' => $isbn],
                [
                    'judul' => $judul,
                    'penulis' => $penulis,
                    'penerbit' => $sumber,
                    'tahun_terbit' => 2025,
                    'kategori_id' => $kat[$kategori] ?? array_values($kat)[0],
                    'stok' => fake()->numberBetween(2, 12),
                    'sinopsis' => "E-book terbuka tentang {$topik}. Dapat dibaca online secara gratis melalui {$sumber}.",
                    'file_url' => $url,
                ]
            );

            $ids = array_values(array_filter(array_map(fn ($p) => $prodi[$p] ?? null, ['SI', 'IF', 'TI'])));
            $buku->prodis()->syncWithoutDetaching($ids);
        }
    }
}