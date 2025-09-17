<?php
session_start();
require_once '../../includes/db.php';

// Function to fetch materi
function fetchMateri($pdo)
{
    try {
        $stmt_materi = $pdo->query("SELECT * FROM materi");
        return $stmt_materi->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die("Error fetching materi: " . $e->getMessage());
    }
}

// Function to fetch kuis
function fetchKuis($pdo)
{
    try {
        $stmt_kuis = $pdo->query("SELECT * FROM kuis");
        return $stmt_kuis->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die("Error fetching kuis: " . $e->getMessage());
    }
}
// Function to process submission
function processSubmission($kuis, &$jawaban_siswa)
{
    $results = [];
    $score = 0;
    $total_questions = count($kuis);
    $score_per_question = 100 / $total_questions;

    foreach ($kuis as $index => $q) {
        $correct = $q['jawaban_benar'];
        $user_answer = isset($jawaban_siswa[$index]) ? $jawaban_siswa[$index] : null;
        $is_correct = ($user_answer === $correct);
        if ($is_correct) {
            $score += $score_per_question;
        }
        $results[] = [
            'question' => $q['pertanyaan'],
            'correct_answer' => $correct,
            'user_answer' => $user_answer,
            'is_correct' => $is_correct,
            'options' => [
                'a' => $q['pilihan_a'],
                'b' => $q['pilihan_b'],
                'c' => $q['pilihan_c'],
                'd' => $q['pilihan_d']
            ]
        ];
    }

    // Pembulatan ke bilangan bulat agar hasilnya 100, bukan 99.999...
    $score = round($score);

    return ['score' => $score, 'results' => $results];
}

// Initialize variables
$materi = fetchMateri($pdo);
$kuis = fetchKuis($pdo);
$feedback = '';
$current_question = 0;
$results = [];

if (!isset($_SESSION['jawaban_siswa'])) {
    $_SESSION['jawaban_siswa'] = [];
}
$jawaban_siswa = &$_SESSION['jawaban_siswa'];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['jawaban'])) {
        foreach ($_POST['jawaban'] as $index => $jawaban) {
            $jawaban_siswa[$index] = $jawaban;
        }
    }

    if (isset($_POST['current_question'])) {
        $current_question = (int)$_POST['current_question'];
    }

    if (isset($_POST['next'])) {
        $current_question = min($current_question + 1, count($kuis) - 1);
    } elseif (isset($_POST['prev'])) {
        $current_question = max($current_question - 1, 0);
    } elseif (isset($_POST['submit'])) {
        $submission_result = processSubmission($kuis, $jawaban_siswa);
        $score = $submission_result['score'];
        $results = $submission_result['results'];
        $total_soal = count($kuis);
        $feedback = "Skor Anda: $score dari $total_soal soal.";
        unset($_SESSION['jawaban_siswa']);
    }
}
?>

<?php include_once('../components/header.php') ?>
<?php include_once('../components/nav.php') ?>
<?php if (empty($materi) || empty($kuis)): ?>
    <main class="main-contents quiz-container">
        <div class="container">
            <h1 class="quiz-title">Kuis Belum Ada</h1>
            <p class="quiz-feedback">Maaf, saat ini belum ada latihan.</p>
        </div>
    </main>


<?php else: ?>
    <main class="main-content">
        <div class="container">
            <div class=" quiz-container">
            <section class="quiz-header">
                <h1 class="quiz-title">Kuis: <?= htmlspecialchars($materi['judul_materi']) ?></h1>
                <?php if ($feedback): ?>
                    <p class="quiz-feedback"><strong><?= $feedback ?></strong></p>
                <?php endif; ?>
            </section>

            <?php if (!empty($results)): ?>
                <section class="quiz-results">
                    <h2 class="results-title">Hasil Latihan Siswa</h2>
                    <?php foreach ($results as $index => $result): ?>
                        <div class="result-item">
                            <h3 class="question-title">Soal <?= $index + 1 ?>: <span class="question-text"><?= htmlspecialchars($result['question']) ?></span></h3>
                            <ul class="answer-options">
                                <?php foreach ($result['options'] as $key => $value): ?>
                                    <li class="answer-item 
                                <?= $key === $result['correct_answer'] ? 'correct-answer' : '' ?> 
                                <?= isset($result['user_answer']) && $key === $result['user_answer'] ? 'user-answer' : '' ?>">
                                        <span class="answer-text"><?= htmlspecialchars($value) ?></span>
                                        <?php if ($key === $result['correct_answer']): ?>
                                            <span class="answer-label correct-label">(Jawaban Benar)</span>
                                        <?php endif; ?>
                                        <?php if (isset($result['user_answer']) && $key === $result['user_answer'] && $key !== $result['correct_answer']): ?>
                                            <span class="answer-label wrong-label">(Jawaban Anda)</span>
                                        <?php endif; ?>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                            <p class="status-text">
                                Status:
                                <span class="status-label <?= $result['is_correct'] ? 'status-correct' : 'status-wrong' ?>">
                                    <?= $result['is_correct'] ? 'Benar' : (isset($result['user_answer']) ? 'Salah' : 'Tidak Dijawab') ?>
                                </span>
                            </p>
                        </div>
                    <?php endforeach; ?>
                    <div class="quiz-actions">
                        <a href="kuis.php" class="btn btn-restart">Ulangi Latihan Siswa</a>
                    </div>
                </section>
            <?php else: ?>
                <section class="quiz-navigation">
                    <h2 class="nav-title">Soal</h2>
                    <div class="nav-grid">
                        <?php for ($i = 0; $i < count($kuis); $i++): ?>
                            <button type="button" class="nav-btn <?= isset($jawaban_siswa[$i]) ? 'answered' : '' ?>" data-question="<?= $i ?>">
                                Soal <?= $i + 1 ?>
                            </button>
                        <?php endfor; ?>
                    </div>
                </section>

                <form method="POST" id="quiz-form" class="quiz-form">
                    <article class="question-container">
                        <fieldset class="question-fieldset">
                            <legend class="question-title">Soal <?= $current_question + 1 ?>: <?= htmlspecialchars($kuis[$current_question]['pertanyaan']) ?></legend>
                            <div class="option-list">
                                <?php
                                $options = [
                                    'a' => $kuis[$current_question]['pilihan_a'],
                                    'b' => $kuis[$current_question]['pilihan_b'],
                                    'c' => $kuis[$current_question]['pilihan_c'],
                                    'd' => $kuis[$current_question]['pilihan_d'],
                                ];
                                foreach ($options as $key => $value): ?>
                                    <label class="option-label">
                                        <input type="radio" class="option-input" name="jawaban[<?= $current_question ?>]" value="<?= $key ?>"
                                            <?= (isset($jawaban_siswa[$current_question]) && $jawaban_siswa[$current_question] === $key) ? 'checked' : '' ?>>
                                        <?= htmlspecialchars($value) ?>
                                    </label><br>
                                <?php endforeach; ?>
                            </div>
                        </fieldset>
                        <img src="../assets/undraw_quiz_zvhe.svg" class="alutrasi" alt="">
                    </article>

                    <input type="hidden" name="current_question" value="<?= $current_question ?>" id="current-question">

                    <div class="nav-buttons">
                        <?php if ($current_question > 0): ?>
                            <button type="submit" name="prev" class="btn btn-prev">Sebelumnya</button>
                        <?php endif; ?>
                        <?php if ($current_question < count($kuis) - 1): ?>
                            <button type="submit" name="next" class="btn btn-next">Selanjutnya</button>
                        <?php else: ?>
                            <button type="submit" name="submit" class="btn btn-submit">Submit Jawaban</button>
                        <?php endif; ?>
                    </div>
                </form>
            <?php endif; ?>
        </div>
        </div>
    </main>
<?php endif; ?>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const navButtons = document.querySelectorAll('.nav-btn');
        const currentQuestionInput = document.getElementById('current-question');
        const quizForm = document.getElementById('quiz-form');

        navButtons.forEach(button => {
            button.addEventListener('click', function() {
                const questionIndex = this.getAttribute('data-question');
                currentQuestionInput.value = questionIndex;
                quizForm.submit();
            });
        });
        
    // Event listener untuk tombol toggle tema
    document.getElementById("toggle-theme")?.addEventListener("click", toggleTheme);

   
        const navbarList = document.getElementById("navbarList");
        const menuToggle = document.getElementById("menuToggle");
    
        // Fungsi untuk toggle navbar
        function toggleNavbar() {
            navbarList.classList.toggle("open");
        }
    
        // Event listener untuk tombol toggle
        menuToggle.addEventListener("click", (e) => {
            e.stopPropagation(); // Mencegah event bubbling
            toggleNavbar();
        });
    
        // Tutup navbar jika klik di luar menu
        document.addEventListener("click", (event) => {
            if (!navbarList.contains(event.target) && !menuToggle.contains(event.target)) {
                navbarList.classList.remove("open");
            }
        });
    

    // Scroll halus untuk semua link
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            target?.scrollIntoView({ behavior: 'smooth' });

            // Tutup menu setelah klik
            navbarList.classList.remove("open");
        });
    });
    });
</script>

<?php include_once('../components/footer.php') ?>
