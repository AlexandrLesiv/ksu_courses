-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Хост: localhost:8889
-- Время создания: Мар 14 2023 г., 08:51
-- Версия сервера: 5.7.39
-- Версия PHP: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `courses`
--

-- --------------------------------------------------------

--
-- Структура таблицы `auth_assignment`
--

CREATE TABLE `auth_assignment` (
  `item_name` varchar(64) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Роль',
  `user_id` int(11) NOT NULL COMMENT 'Користувач',
  `created_at` int(11) DEFAULT NULL COMMENT 'Створено'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `auth_assignment`
--

INSERT INTO `auth_assignment` (`item_name`, `user_id`, `created_at`) VALUES
('admin', 1, 1677679500),
('student', 3, 1678432368),
('teacher', 2, 1677778949);

-- --------------------------------------------------------

--
-- Структура таблицы `auth_item`
--

CREATE TABLE `auth_item` (
  `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Назва',
  `type` smallint(6) NOT NULL COMMENT 'Тип',
  `description` text COLLATE utf8_unicode_ci COMMENT 'Опис',
  `rule_name` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Назва правила',
  `data` blob COMMENT 'Дані',
  `created_at` int(11) DEFAULT NULL COMMENT 'Створено',
  `updated_at` int(11) DEFAULT NULL COMMENT 'Відредаговано'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `auth_item`
--

INSERT INTO `auth_item` (`name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) VALUES
('admin', 1, 'Адміністратор', NULL, NULL, 1677679500, 1677679500),
('student', 1, 'Здобувач освіти', NULL, NULL, 1677679500, 1677679500),
('teacher', 1, 'Викладач', NULL, NULL, 1677679500, 1677679500);

-- --------------------------------------------------------

--
-- Структура таблицы `auth_item_child`
--

CREATE TABLE `auth_item_child` (
  `parent` varchar(64) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Батьківська роль',
  `child` varchar(64) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Дочірня роль'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `auth_rule`
--

CREATE TABLE `auth_rule` (
  `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Назва',
  `data` blob COMMENT 'Дані',
  `created_at` int(11) DEFAULT NULL COMMENT 'Створено',
  `updated_at` int(11) DEFAULT NULL COMMENT 'Відредаговано'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `classes`
--

CREATE TABLE `classes` (
  `id` int(11) NOT NULL COMMENT 'ID',
  `course_id` int(11) NOT NULL COMMENT 'Курс',
  `title` varchar(10) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Назва групи',
  `students_count` int(11) UNSIGNED NOT NULL COMMENT 'Кількість студентів',
  `created_at` int(11) NOT NULL COMMENT 'Створено',
  `updated_at` int(11) NOT NULL COMMENT 'Відредаговано'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `classes`
--

INSERT INTO `classes` (`id`, `course_id`, `title`, `students_count`, `created_at`, `updated_at`) VALUES
(1, 1, 'A-10', 10, 1678095740, 1678100505),
(2, 1, 'A-11', 10, 1678095856, 1678095856),
(3, 1, 'A-12', 10, 1678098998, 1678098998);

-- --------------------------------------------------------

--
-- Структура таблицы `class_students`
--

CREATE TABLE `class_students` (
  `id` int(11) NOT NULL COMMENT 'ID',
  `course_id` int(11) NOT NULL COMMENT 'Курс',
  `class_id` int(11) DEFAULT NULL COMMENT 'Клас',
  `student_id` int(11) NOT NULL COMMENT 'Студент',
  `status` enum('Новий','Підтверджено','Відхилено') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Новий' COMMENT 'Статус',
  `created_at` int(11) NOT NULL COMMENT 'Створено',
  `updated_at` int(11) NOT NULL COMMENT 'Відредаговано'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `class_students`
--

INSERT INTO `class_students` (`id`, `course_id`, `class_id`, `student_id`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 3, 'Підтверджено', 1678447133, 1678450408);

-- --------------------------------------------------------

--
-- Структура таблицы `content`
--

CREATE TABLE `content` (
  `id` int(11) NOT NULL COMMENT 'ID',
  `title` varchar(150) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Назва сторінки',
  `description` varchar(300) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Опис',
  `keywords` varchar(300) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Ключові слова',
  `text` text COLLATE utf8_unicode_ci NOT NULL COMMENT 'Вміст',
  `url` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT 'URL',
  `updated_at` int(11) NOT NULL COMMENT 'Відредаговано'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `content`
--

INSERT INTO `content` (`id`, `title`, `description`, `keywords`, `text`, `url`, `updated_at`) VALUES
(1, 'Інформація про сервіс відео-курси онлайн', 'Офіційний веб-додаток для публікації, керування онлайн відео-курсами створений для учнів, студентів, які користуються перевагами онлайн-освіти: обирайте теми, які вас цікавлять, отримуйте мегакорисну інформацію від провідних учителів та працівників галузі освіти з усієї України.', 'StudyAdd, відео-курси онлайн, інформація, Yii2', '<p>Наш веб-додаток забезпечує доступ до великої кількості онлайн відео-курсів з різних тематик. Ми прагнемо забезпечити найкращий досвід навчання для наших користувачів, що дозволяє їм навчатися в будь-який зручний час і з будь-якого місця.</p>\r\n\r\n<blockquote>\r\n<p><strong>&quot;Навчання - це засіб змінювати світ.&quot;</strong> - Нельсон Мандела</p>\r\n</blockquote>\r\n\r\n<p>Наші курси розроблені професійними викладачами та експертами у різних галузях, що гарантує високу якість та актуальність матеріалів. Кожен курс складається з відеоуроків, які можна переглядати в будь-який час, а також з додаткових матеріалів, таких як тексти, завдання та тестування.</p>\r\n\r\n<p>Ми пропонуємо різні рівні складності курсів - від початкового до експертного, що дозволяє кожному користувачеві вибрати той, що найбільше підходить для його потреб. Крім того, ми постійно додаємо нові курси, щоб забезпечити нашим користувачам найактуальнішу інформацію.</p>\r\n\r\n<p>Наш веб-додаток також має дружній та зручний інтерфейс, що дозволяє швидко та легко знайти потрібний курс та розпочати навчання. Крім того, ми пропонуємо підтримку користувачів, яка готова відповісти на будь-які питання та допомогти з вирішенням проблем.</p>\r\n\r\n<p>Загалом, наш веб-додаток - це ідеальне місце для тих, хто бажає отримати нові знання та навички в зручний для себе спосіб.</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p style=\"text-align: center;\"><strong>Щоб отримати доступ до всіх наших курсів - створіть акаунт та перейдіть у власний обліковий запис.</strong></p>\r\n', '/site/index', 1678783879),
(2, 'Політика конфіденційності', 'Ця політика конфіденційності пояснює, як ми збираємо, використовуємо та захищаємо вашу особисту інформацію, коли ви відвідуєте наш веб-додаток. Ми зобов\'язані забезпечувати конфіденційність вашої інформації та захищати вашу приватність.', 'StudyAdd, веб-додаток, політика конфіденційності, захист даних, Yii2', '<p>Ця політика конфіденційності пояснює, як ми збираємо, використовуємо та захищаємо вашу особисту інформацію, коли ви відвідуєте наш веб-додаток. Ми зобов&#39;язані забезпечувати конфіденційність вашої інформації та захищати вашу приватність. Якщо ми просимо вас надати певну інформацію, за якою можна ідентифікувати вас при використанні цього веб-додатку, ви можете бути впевнені, що вона буде використана лише згідно з цією політикою конфіденційності.</p>\r\n\r\n<p><strong>1. Які дані ми збираємо</strong></p>\r\n\r\n<p>Ми можемо збирати наступні особисті дані про вас:</p>\r\n\r\n<ul>\r\n	<li>ім&#39;я та прізвище;</li>\r\n	<li>контактну інформацію, включаючи адресу електронної пошти;</li>\r\n	<li>інформацію про ваше місце роботи та посаду;</li>\r\n	<li>інформацію про ваші інтереси та вподобання, які ви надаєте в процесі використання нашого веб-додатку.</li>\r\n</ul>\r\n\r\n<p><strong>2. Як ми використовуємо зібрані дані</strong></p>\r\n\r\n<p>Ми використовуємо зібрані дані про вас для наступних цілей:</p>\r\n\r\n<ul>\r\n	<li>для забезпечення вам доступу до функцій та послуг нашого веб-веб-додатку;</li>\r\n	<li>для зв&#39;язку з вами щодо послуг та пропозицій, які можуть вас зацікавити;</li>\r\n	<li>для аналізу та вдосконалення роботи нашого веб-додатку;</li>\r\n	<li>для забезпечення виконання законодавства та наших внутрішніх процедур.</li>\r\n</ul>\r\n\r\n<p><strong>3. Як ми захищаємо ваші дані</strong></p>\r\n\r\n<p>Ми робимо все можливе, щоб забезпечити безпеку вашої особистої інформації та захист&nbsp;її від несанкціонованого доступу, використання чи розголошення. Для цього ми використовуємо технології безпеки та стандарти безпеки, щоб захистити ваші дані від небажаних дій третіх сторін.</p>\r\n\r\n<p><strong>4. Хто має доступ до ваших даних</strong></p>\r\n\r\n<p>Ми не передаємо ваші дані третім сторонам, за винятком випадків, коли це необхідно для надання вам послуг або виконання законодавства.</p>\r\n\r\n<p><strong>5. Як довго ми зберігаємо ваші дані</strong></p>\r\n\r\n<p>Ми зберігаємо ваші дані лише стільки часу, скільки це необхідно для досягнення тих цілей, для яких вони були зібрані, або згідно з вимогами законодавства.</p>\r\n\r\n<p><strong>6. Як ви можете контролювати використання своїх даних</strong></p>\r\n\r\n<p>Ви можете скористатися наступними правами щодо використання своїх даних:</p>\r\n\r\n<ul>\r\n	<li>вимагати видалення своїх даних;</li>\r\n	<li>вимагати виправлення неточних даних;</li>\r\n	<li>відкликати свою згоду на обробку даних.</li>\r\n</ul>\r\n\r\n<p>Якщо ви бажаєте скористатися своїми правами, будь ласка, зв&#39;яжіться з нами за допомогою <a href=\"/site/contact\" target=\"_blank\">контактної інформації</a>, яку ми надаємо на нашому веб-додатку.</p>\r\n\r\n<p><strong>7. Зміни до політики конфіденційності</strong></p>\r\n\r\n<p>Ми можемо час від часу змінювати цю політику конфіденційності. Якщо ми вносимо значні зміни, ми повідомимо вас про це шляхом розміщення оновленої політики конфіденційності на нашому веб-додатку або іншими способами, наприклад, електронною поштою.</p>\r\n\r\n<p><strong>8. Контактна інформація</strong></p>\r\n\r\n<p>Якщо у вас є будь-які запитання щодо цієї політики конфіденційності або якщо ви бажаєте скористатися своїми правами щодо використання своїх даних, будь ласка, зв&#39;яжіться з нами за допомогою наступної контактної інформації:</p>\r\n\r\n<p>StudyApp.Inc</p>\r\n\r\n<p>+38 (044) 123 45 67</p>\r\n\r\n<p><a href=\"mailto:support@courses.local\">support@courses.local</a></p>\r\n\r\n<p>Будь ласка, не соромтеся зв&#39;язатися з нами, якщо у вас є будь-які запитання або побажання щодо цієї політики конфіденційності. Ми завжди готові допомогти вам і відповісти на всі ваші запитання. Дякуємо, що скористалися нашими послугами!</p>\r\n', '/site/policy', 1677849844),
(3, 'Контакти', 'Контактна інформація для звʼязку з адміністрацією веб-додатку. Будь ласка, не соромтеся зв\'язатися з нами, якщо у вас виникли якісь запитання.', 'StudyAdd, веб-додаток, контакти, форма зворотнього звʼязку, Yii2', '<p>Ви можете надіслати нам форму зворотнього звʼязку, або скористатися будь-яким іншим зручним для вас способом. Наші контакти представлено нижче:</p>\r\n\r\n<ul>\r\n	<li><strong>StudyApp.Inc</strong></li>\r\n	<li><strong>Телефон:</strong>&nbsp;+38 (044) 123 45 67</li>\r\n	<li><strong>E-mail:</strong>&nbsp;<a href=\"mailto:support@courses.local\" style=\"box-sizing: border-box; color: var(--first-color-alt); text-decoration-line: none; font-weight: var(--font-medium); transition: all 0.35s ease-in-out 0s; outline: none; margin-bottom: unset;\">support@courses.local</a></li>\r\n</ul>\r\n', '/site/contact', 1677865571);

-- --------------------------------------------------------

--
-- Структура таблицы `courses`
--

CREATE TABLE `courses` (
  `id` int(11) NOT NULL COMMENT 'ID',
  `teacher_id` int(11) NOT NULL COMMENT 'Викладач',
  `title` varchar(300) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Назва курсу',
  `description` varchar(500) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Опис',
  `keywords` varchar(300) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Ключові слова',
  `students_count` int(11) UNSIGNED NOT NULL COMMENT 'Кількість студентів',
  `status` enum('Новий','В процесі','Йде набір','Завершено','Заблоковано') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Новий' COMMENT 'Статус',
  `created_at` int(11) NOT NULL COMMENT 'Створено',
  `updated_at` int(11) NOT NULL COMMENT 'Відредаговано'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `courses`
--

INSERT INTO `courses` (`id`, `teacher_id`, `title`, `description`, `keywords`, `students_count`, `status`, `created_at`, `updated_at`) VALUES
(1, 2, 'HTML з нуля до джуна', 'Вивчаємо основи HTML і CSS. На практиці розбираємося з семантичною розміткою і базовими механізмами стилізації на прикладі невеликого сайту. Ефективна нова програма і постійна підтримка. 90% випускників знайшли роботу в IT. Більше 2000 працевлаштовано в ІТ-компаніях.', 'html, css, семантична верстка, front-end', 30, 'В процесі', 1678029814, 1678544025);

-- --------------------------------------------------------

--
-- Структура таблицы `favorites`
--

CREATE TABLE `favorites` (
  `id` int(11) NOT NULL COMMENT 'ID',
  `course_id` int(11) NOT NULL COMMENT 'Курс',
  `student_id` int(11) NOT NULL COMMENT 'Студент',
  `created_at` int(11) NOT NULL COMMENT 'Створено'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `favorites`
--

INSERT INTO `favorites` (`id`, `course_id`, `student_id`, `created_at`) VALUES
(1, 1, 3, 1678444752);

-- --------------------------------------------------------

--
-- Структура таблицы `forum`
--

CREATE TABLE `forum` (
  `id` int(11) NOT NULL COMMENT 'ID',
  `user_id` int(11) NOT NULL COMMENT 'Користувач',
  `course_id` int(11) NOT NULL COMMENT 'Курс',
  `text` text COLLATE utf8_unicode_ci NOT NULL COMMENT 'Повідомлення',
  `created_at` int(11) NOT NULL COMMENT 'Створено'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `forum`
--

INSERT INTO `forum` (`id`, `user_id`, `course_id`, `text`, `created_at`) VALUES
(1, 2, 1, 'Привіт, це тестове повідомлення.', 1678779276),
(2, 3, 1, 'Дуже цікавий курс.', 1678783115);

-- --------------------------------------------------------

--
-- Структура таблицы `homework`
--

CREATE TABLE `homework` (
  `id` int(11) NOT NULL COMMENT 'ID',
  `student_id` int(11) NOT NULL COMMENT 'Студент',
  `lesson_id` int(11) NOT NULL COMMENT 'Заняття',
  `description` text COLLATE utf8_unicode_ci COMMENT 'Примітка',
  `comment` text COLLATE utf8_unicode_ci COMMENT 'Коментар',
  `file` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Файл',
  `created_at` int(11) NOT NULL COMMENT 'Створено',
  `updated_at` int(11) NOT NULL COMMENT 'Відредаговано'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `homework`
--

INSERT INTO `homework` (`id`, `student_id`, `lesson_id`, `description`, `comment`, `file`, `created_at`, `updated_at`) VALUES
(6, 3, 1, 'Приклад домашньої роботи.', '<p>Гарна робота.</p>\r\n', 'fed75af442336905422abea13ac4f332.docx', 1678631391, 1678631845);

-- --------------------------------------------------------

--
-- Структура таблицы `lessons`
--

CREATE TABLE `lessons` (
  `id` int(11) NOT NULL COMMENT 'ID',
  `course_id` int(11) NOT NULL COMMENT 'Курс',
  `title` varchar(300) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Назва заняття',
  `description` varchar(500) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Опис',
  `keywords` varchar(300) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Ключові слова',
  `created_at` int(11) NOT NULL COMMENT 'Створено',
  `updated_at` int(11) NOT NULL COMMENT 'Відредаговано'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `lessons`
--

INSERT INTO `lessons` (`id`, `course_id`, `title`, `description`, `keywords`, `created_at`, `updated_at`) VALUES
(1, 1, 'Основи HTML', 'Історія та структура HTML. Ваш перший HTML-документ. Структура базового HTML-документа. Описування тексту, заголовків та параграфів. Теги та атрибути.', 'HTML, структура документа, теги, атрибути', 1678268488, 1678273265),
(2, 1, 'Робота з посиланнями та зображеннями', 'Створення гіперпосилань та як вони працюють. Додавання зображень до сторінки. Розміщення та оформлення зображень.', 'гіперпосилання, зображення, якір, mailto, target', 1678268589, 1678268589),
(3, 1, 'Таблиці та форми', 'Створення таблиць для відображення даних. Оформлення та стилізація таблиць. Створення форм та їх елементів.', 'таблиця, форма, input, чекбокс, випадаючий список', 1678268676, 1678268676),
(4, 1, 'Створення списків', 'Списки з маркерами. Списки з номерами. Вкладені списки.', 'списки, нумерованані списки, маркеровані списки, вкладені списки', 1678268732, 1678268732),
(5, 1, 'Робота з CSS', 'Вступ до CSS та його ролі в оформленні сторінок. Селектори CSS. Кольори та фони. Розміри та обмеження. Шрифти та текст.', 'CSS, селектори, колір, фон, розміри, шрифт, текст', 1678268797, 1678268797),
(6, 1, 'Аудіо та відео', 'Вставлення відео та аудіо на сторінку. Підтримка різних форматів відео та аудіо. Відео та аудіо на різних пристроях.', 'аудіо, відео, iframe', 1678268843, 1678268843),
(7, 1, 'Робота з форматуванням та стилізацією', 'Використання тегів для форматування тексту. Створення стилів CSS та їх використання для стилізації сторінки. Оформлення заголовків, тексту, списків, таблиць та форм.', 'тег, форматування, CSS, оформлення', 1678268900, 1678268900);

-- --------------------------------------------------------

--
-- Структура таблицы `marks`
--

CREATE TABLE `marks` (
  `id` int(11) NOT NULL COMMENT 'ID',
  `student_id` int(11) NOT NULL COMMENT 'Студент',
  `lesson_id` int(11) NOT NULL COMMENT 'Заняття',
  `value` int(3) NOT NULL COMMENT 'Оцінка',
  `created_at` int(11) NOT NULL COMMENT 'Створено',
  `updated_at` int(11) NOT NULL COMMENT 'Відредаговано'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `media`
--

CREATE TABLE `media` (
  `id` int(11) NOT NULL COMMENT 'ID',
  `lesson_id` int(11) NOT NULL COMMENT 'Заняття',
  `title` varchar(300) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Назва/заголовок',
  `description` text COLLATE utf8_unicode_ci COMMENT 'Текст/примітка',
  `type` enum('Відео','Домашня робота','Зображення','Текст','Файл') COLLATE utf8_unicode_ci NOT NULL COMMENT 'Тип',
  `file` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Файл',
  `created_at` int(11) NOT NULL COMMENT 'Створено',
  `updated_at` int(11) NOT NULL COMMENT 'Відредаговано'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `media`
--

INSERT INTO `media` (`id`, `lesson_id`, `title`, `description`, `type`, `file`, `created_at`, `updated_at`) VALUES
(1, 1, 'Історія та структура HTML', '<p><strong>HTML (Hypertext Markup Language)&nbsp;</strong>- це стандартна мова розмітки для створення веб-сторінок. HTML забезпечує структуру веб-сторінки, включаючи заголовки, параграфи, списки, таблиці та інші елементи.</p>\r\n\r\n<p><strong>Історія HTML:</strong></p>\r\n\r\n<p>HTML був розроблений Тімом Бернерс-Лі в 1989 році як частина проекту про спільне використання інформації (World Wide Web). Перша версія HTML була опублікована у 1993 році як HTML 1.0. З того часу було розроблено декілька версій HTML, кожна з яких містить нові функції та поліпшення.</p>\r\n\r\n<p><strong>Структура HTML:</strong></p>\r\n\r\n<p>HTML складається з рядків коду, що називаються тегами. Теги вказують браузеру, які елементи веб-сторінки відображати та як їх відображати.</p>\r\n\r\n<p><strong>Основні складові HTML структури:</strong></p>\r\n\r\n<ol>\r\n	<li><strong><em>&lt;!DOCTYPE html&gt;</em></strong> - це оголошення типу документа і вказує на стандарт, за яким повинна бути створена веб-сторінка.</li>\r\n	<li><strong><em>&lt;html&gt;</em></strong> - вказує на те, що це початок HTML документа.</li>\r\n	<li><strong><em>&lt;head&gt;</em></strong> - містить інформацію про документ, яка не відображається на сторінці, таку як метатеги, підключення до стилів, скрипти, тощо.</li>\r\n	<li><strong><em>&lt;title&gt;</em></strong> - вказує на назву сторінки, яка відображається у вкладці браузера та у пошукових системах.</li>\r\n	<li><strong><em>&lt;body&gt;</em></strong> - містить зміст сторінки, такий як текст, зображення, відео, форми, таблиці тощо.</li>\r\n</ol>\r\n\r\n<p>Це основні елементи HTML структури. Щоб створити веб-сторінку, необхідно додати теги з різними атрибутами та значеннями всередину <strong><em>&lt;body&gt;</em></strong> тега.</p>\r\n', 'Текст', NULL, 1678367171, 1678379358),
(2, 1, 'Ваш перший HTML-документ', '<p>Щоб створити перший HTML-документ, потрібно виконати наступні кроки:</p>\r\n\r\n<ol>\r\n	<li>Відкрийте будь-який текстовий редактор, наприклад, Блокнот (<em>Notepad</em>) на Windows або Текстовий редактор (<em>TextEdit</em>) на Mac.</li>\r\n	<li>Створіть новий файл із розширенням .html, наприклад, <em>index.html</em>. Розширення .html вказує на те, що це HTML-документ.</li>\r\n	<li>Увійдіть у режим редагування та додайте наступний код, який зображено на малюнку нижче.</li>\r\n	<li>Збережіть файл та відкрийте його в будь-якому браузері.</li>\r\n</ol>\r\n', 'Текст', NULL, 1678376416, 1678376416),
(3, 1, 'Перший HTML-документ', '', 'Зображення', 'f744635e3c943f0c6e15d11c36cd287a.png', 1678376468, 1678379408),
(4, 1, 'Структура базового HTML-документа', '<p style=\"margin-left:0px; margin-right:0px; text-align:justify\">HTML складається з рядків коду, що називаються тегами. Теги вказують браузеру, які елементи веб-сторінки відображати та як їх відображати.</p>\r\n\r\n<p style=\"margin-left:0px; margin-right:0px; text-align:justify\">Основні складові HTML структури:</p>\r\n\r\n<ol style=\"margin-left:var(--mb-3)\">\r\n	<li><em>&lt;!DOCTYPE html&gt;</em>&nbsp;- це оголошення типу документа і вказує на стандарт, за яким повинна бути створена веб-сторінка.</li>\r\n	<li><em>&lt;html&gt;</em>&nbsp;- вказує на те, що це початок HTML документа.</li>\r\n	<li><em>&lt;head&gt;</em>&nbsp;- містить інформацію про документ, яка не відображається на сторінці, таку як метатеги, підключення до стилів, скрипти, тощо.</li>\r\n	<li><em>&lt;title&gt;</em>&nbsp;- вказує на назву сторінки, яка відображається у вкладці браузера та у пошукових системах.</li>\r\n	<li><em>&lt;body&gt;</em>&nbsp;- містить зміст сторінки, такий як текст, зображення, відео, форми, таблиці тощо.</li>\r\n</ol>\r\n\r\n<p style=\"margin-left:0px; margin-right:0px; text-align:justify\">Це основні елементи HTML структури. Щоб створити веб-сторінку, необхідно додати теги з різними атрибутами та значеннями всередину&nbsp;<em>&lt;body&gt;</em>&nbsp;тега.</p>\r\n', 'Текст', NULL, 1678376636, 1678376636),
(5, 1, 'Структура базового HTML-документа', '', 'Зображення', 'd70ac4a284fe3d9d4d9dca11ae07cf67.png', 1678376739, 1678376739),
(6, 1, 'Описування тексту, заголовків та параграфів.', '<p style=\"margin-left:0px; margin-right:0px; text-align:justify\">Текст, заголовки та параграфи можна описати мовою HTML за допомогою наступних тегів:</p>\r\n\r\n<p><strong>1. Заголовки</strong></p>\r\n\r\n<p style=\"margin-left:0px; margin-right:0px; text-align:justify\">Для створення заголовків використовуються теги&nbsp;<strong><em>&lt;h1&gt;</em></strong>&nbsp;до&nbsp;<strong><em>&lt;h6&gt;</em></strong>, де&nbsp;<strong><em>&lt;h1&gt;</em></strong>&nbsp;є найбільшим заголовком, а&nbsp;<strong><em>&lt;h6&gt;</em></strong>&nbsp;&mdash; найменшим.</p>\r\n\r\n<p><strong>2. Параграфи</strong></p>\r\n\r\n<p style=\"margin-left:0px; margin-right:0px; text-align:justify\">Для створення параграфів використовується тег&nbsp;<strong><em>&lt;p&gt;</em></strong>.</p>\r\n\r\n<p><strong>3. Текст</strong></p>\r\n\r\n<p style=\"margin-left:0px; margin-right:0px; text-align:justify\">Простий текст в HTML коду додається без будь-яких тегів.</p>\r\n\r\n<p style=\"margin-left:0px; margin-right:0px; text-align:justify\">Але для форматування тексту, наприклад, для виділення жирним або курсивним шрифтом, використовуються теги&nbsp;<strong><em>&lt;strong&gt;</em></strong>&nbsp;і&nbsp;<strong><em>&lt;em&gt;</em></strong>&nbsp;відповідно.</p>\r\n\r\n<p style=\"margin-left:0px; margin-right:0px; text-align:justify\">Також можна використовувати інші теги для форматування тексту, такі як&nbsp;<strong><em>&lt;u&gt;</em></strong>&nbsp;для підкреслення,&nbsp;<strong><em>&lt;sup&gt;</em></strong>&nbsp;та&nbsp;<strong><em>&lt;sub&gt;</em></strong>&nbsp;для надрядкових та підрядкових значень і т.д.</p>\r\n', 'Текст', NULL, 1678377244, 1678377244),
(7, 1, 'Теги та атрибути', '<p>У мові HTML існує багато тегів та атрибутів, які використовуються для створення веб-сторінок та їх форматування. Ось кілька прикладів:</p>\r\n\r\n<p><strong>1. Теги</strong></p>\r\n\r\n<p>Теги в HTML використовуються для створення різних елементів на веб-сторінці. Основні теги включають в себе:</p>\r\n\r\n<ul>\r\n	<li><em><strong>&lt;html&gt;</strong></em> - кореневий елемент HTML-документа;</li>\r\n	<li><em><strong>&lt;head&gt;</strong></em> - визначає метадані документа, такі як заголовок, метатеги, сценарії та CSS-стилі;</li>\r\n	<li><em><strong>&lt;body&gt;</strong></em> - визначає вміст документа;</li>\r\n	<li><em><strong>&lt;div&gt;</strong></em> - визначає контейнер, що містить інші HTML-елементи;</li>\r\n	<li><em><strong>&lt;p&gt;</strong></em> - визначає абзац;</li>\r\n	<li><em><strong>&lt;img&gt;</strong></em> - визначає зображення на веб-сторінці;</li>\r\n	<li><em><strong>&lt;a&gt;</strong></em> - визначає гіперпосилання на іншу сторінку або файл;</li>\r\n	<li><em><strong>&lt;ul&gt;</strong></em> - визначає список з маркерами;</li>\r\n	<li><em><strong>&lt;ol&gt;</strong></em> - визначає нумерований список;</li>\r\n	<li><em><strong>&lt;li&gt;</strong></em> - визначає елемент списку.</li>\r\n</ul>\r\n\r\n<p><strong>2. Атрибути</strong></p>\r\n\r\n<p>Атрибути в HTML використовуються для додаткової настройки елементів. Основні атрибути включають в себе:</p>\r\n\r\n<ul>\r\n	<li><em><strong>class</strong></em> - визначає CSS-клас для елемента;</li>\r\n	<li><em><strong>id</strong></em> - визначає унікальний ідентифікатор для елемента;</li>\r\n	<li><em><strong>style</strong></em> - визначає inline CSS-стилі для елемента;</li>\r\n	<li><em><strong>src</strong></em> - визначає шлях до джерела зображення або мультимедійного вмісту;</li>\r\n	<li><em><strong>href</strong></em> - визначає адресу гіперпосилання;</li>\r\n	<li><em><strong>alt</strong></em> - визначає альтернативний текст для зображення;</li>\r\n	<li><em><strong>title</strong></em> - визначає заголовок для елемента;</li>\r\n	<li><em><strong>target</strong></em> - визначає місце, де буде відкрита сторінка, що відповідає гіперпосиланню.</li>\r\n</ul>\r\n\r\n<p>Це лише декілька прикладів тегів та атрибутів, які використовуються в HTML. У мові HTML є багато інших тегів та атрибутів.</p>\r\n\r\n<p><strong>3. Теги для форматування тексту</strong></p>\r\n\r\n<p>HTML містить теги для форматування тексту, які дозволяють визначити розмір шрифту, курсив, жирний або підкреслений текст, колір тексту та інші параметри. Основні теги для форматування тексту включають в себе:</p>\r\n\r\n<ul>\r\n	<li><em><strong>&lt;b&gt;</strong></em> - визначає жирний текст;</li>\r\n	<li><em><strong>&lt;i&gt;</strong></em> - визначає курсивний текст;</li>\r\n	<li><em><strong>&lt;u&gt;</strong></em> - визначає підкреслений текст;</li>\r\n	<li><em><strong>&lt;strike&gt;</strong></em> - визначає перекреслений текст;</li>\r\n	<li><em><strong>&lt;em&gt;</strong></em> - визначає наголошений текст;</li>\r\n	<li><em><strong>&lt;strong&gt;</strong></em> - визначає виділений текст;</li>\r\n	<li><em><strong>&lt;small&gt;</strong></em> - визначає менший розмір шрифту;</li>\r\n	<li><em><strong>&lt;big&gt;</strong></em> - визначає більший розмір шрифту.</li>\r\n</ul>\r\n\r\n<p><strong>4. Теги для таблиць та списків</strong></p>\r\n\r\n<p>HTML містить теги для створення таблиць та списків, які дозволяють організувати дані на веб-сторінці. Основні теги для таблиць та списків включають в себе:</p>\r\n\r\n<ul>\r\n	<li><em><strong>&lt;table&gt;</strong></em> - визначає таблицю;</li>\r\n	<li><em><strong>&lt;tr&gt;</strong></em> - визначає рядок таблиці;</li>\r\n	<li><em><strong>&lt;th&gt;</strong></em> - визначає заголовок стовпця таблиці;</li>\r\n	<li><em><strong>&lt;td&gt;</strong></em> - визначає комірку таблиці;</li>\r\n	<li><em><strong>&lt;caption&gt;</strong></em> - визначає заголовок таблиці;</li>\r\n	<li><em><strong>&lt;ul&gt;</strong></em> - визначає ненумерований список;</li>\r\n	<li><em><strong>&lt;ol&gt;</strong></em> - визначає нумерований список;</li>\r\n	<li><em><strong>&lt;li&gt;</strong></em> - визначає елемент списку.</li>\r\n</ul>\r\n\r\n<p><strong>5. Атрибути для форматування та стилізації елементів</strong></p>\r\n\r\n<p>HTML містить атрибути для форматування та стилізації елементів, які дозволяють змінювати зовнішній вигляд елементів. Основні атрибути для форматування та стилізації елементів включають в себе:</p>\r\n\r\n<ul>\r\n	<li><em><strong>style</strong></em> - визначає CSS-стилі для елемента;</li>\r\n	<li><em><strong>class</strong></em> - визначає CSS-клас для елемента;</li>\r\n	<li><em><strong>id</strong></em> - визначає унікальний ідентифікатор для елемента;</li>\r\n	<li><em><strong>title</strong></em> - визначає заголовок для елемента.</li>\r\n</ul>\r\n\r\n<p><strong>6. Теги для посилань</strong></p>\r\n\r\n<p>HTML містить теги для створення посилань на інші веб-сторінки або ресурси в Інтернеті. Основні теги для посилань включають в себе:</p>\r\n\r\n<ul>\r\n	<li><em><strong>&lt;a&gt;</strong></em> - визначає посилання;</li>\r\n	<li><em><strong>href</strong></em> - визначає адресу, на яку посилається посилання;</li>\r\n	<li><em><strong>target</strong></em> - визначає місце, де буде відкриватися веб-сторінка або ресурс;</li>\r\n	<li><em><strong>rel</strong></em> - визначає взаємозв&#39;язок між поточною веб-сторінкою та посиланням.</li>\r\n</ul>\r\n\r\n<p><strong>7. Теги для зображень</strong></p>\r\n\r\n<p>HTML містить теги для вставки зображень.&nbsp;Основні теги для зображень включають в себе:</p>\r\n\r\n<ul>\r\n	<li><em><strong>&lt;img&gt;</strong></em>&nbsp;- визначає посилання;</li>\r\n	<li><em><strong>src</strong></em>&nbsp;- визначає адресу на зображення;</li>\r\n	<li><em><strong>alt</strong></em>&nbsp;- визначає альтернативний текст для зображення;</li>\r\n	<li><em><strong>width</strong></em>&nbsp;- визначає ширину зображення;</li>\r\n	<li><em><strong>height</strong></em>&nbsp;- визначає висоту&nbsp;зображенн.</li>\r\n</ul>\r\n', 'Текст', NULL, 1678378147, 1678378147),
(8, 1, 'Домашня робота по темі \"Основи HTML\"', '<p>Створити перший HTML документ та попрактикувати вивчені теги. Надіслати створений файл. Файл назвати&nbsp;<em>index.html.</em></p>\n', 'Домашня робота', 'd70ac4a284fe3d9d4d9dca11ae07cf67.png', 1678548360, 1678548360),
(9, 1, 'Прямий ефір', 'https://youtu.be/j5TqaWCCdUg', 'Відео', NULL, 1678632403, 1678632403);

-- --------------------------------------------------------

--
-- Структура таблицы `teacher_info`
--

CREATE TABLE `teacher_info` (
  `id` int(11) NOT NULL COMMENT 'ID',
  `teacher_id` int(11) NOT NULL COMMENT 'Викладач',
  `info` text COLLATE utf8_unicode_ci NOT NULL COMMENT 'Інформація',
  `created_at` int(11) NOT NULL COMMENT 'Створено',
  `updated_at` int(11) NOT NULL COMMENT 'Відредаговано'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `teacher_info`
--

INSERT INTO `teacher_info` (`id`, `teacher_id`, `info`, `created_at`, `updated_at`) VALUES
(1, 2, '<p><strong>Профіль:&nbsp;</strong>Досвідчений викладач програмування з більш ніж 5-річним досвідом роботи в університетах та інших навчальних закладах. Володію високим рівнем знань та практичного досвіду у таких областях як Java, Python, С++, JavaScript та баз даних. Маю досвід розробки веб-додатків та мобільних додатків. Вмію створювати ефективні та цікаві методи навчання для студентів з будь-яким рівнем знань.</p>\r\n\r\n<p><strong>Досвід роботи:</strong></p>\r\n\r\n<ul>\r\n	<li>\r\n	<p>Викладач програмування в Університеті &quot;Назва університету&quot;, 2018-по теперішній час</p>\r\n\r\n	<ul>\r\n		<li>Викладаю курси Java, Python, С++, JavaScript та баз даних</li>\r\n		<li>Розробляю матеріали для лекцій та практичних занять</li>\r\n		<li>Веду консультації зі студентами щодо навчальних питань</li>\r\n	</ul>\r\n	</li>\r\n	<li>\r\n	<p>Викладач програмування в &quot;Назва навчального закладу&quot;, 2015-2018</p>\r\n\r\n	<ul>\r\n		<li>Викладаю курси Java, Python, С++ та баз даних</li>\r\n		<li>Розробляю матеріали для лекцій та практичних занять</li>\r\n		<li>Веду консультації зі студентами щодо навчальних питань</li>\r\n	</ul>\r\n	</li>\r\n</ul>\r\n\r\n<p><strong>Освіта:</strong></p>\r\n\r\n<ul>\r\n	<li>Магістр комп&#39;ютерних наук, &quot;Назва університету&quot;, 2015</li>\r\n	<li>Бакалавр інформаційних технологій, &quot;Назва університету&quot;, 2013</li>\r\n</ul>\r\n\r\n<p><strong>Навички:</strong></p>\r\n\r\n<ul>\r\n	<li>Мови програмування: Java, Python, С++, JavaScript</li>\r\n	<li>Бази даних: SQL, Oracle, MySQL</li>\r\n	<li>Веб-розробка: HTML, CSS, JavaScript, React, Node.js</li>\r\n	<li>Мобільна розробка: Android, iOS, Flutter</li>\r\n	<li>Методи навчання: лекції, практичні заняття, індивідуальні роботи</li>\r\n	<li>Керівництво проектами: Agile, Scrum</li>\r\n	<li>Інші навички: аналітичне мислення, комунікативність, педагогічні навички</li>\r\n</ul>\r\n\r\n<p><strong>Проекти:</strong></p>\r\n\r\n<ul>\r\n	<li>\r\n	<p>Розробка веб-додатку &quot;Назва проекту&quot;, 2020-2021</p>\r\n\r\n	<ul>\r\n		<li>Використані технології: React, Node.js, MongoDB</li>\r\n		<li>Розробка функціональності, UI/UX дизайн</li>\r\n	</ul>\r\n	</li>\r\n	<li>\r\n	<p>Розробка мобільного додатку &quot;Назва проекту&quot;, 2019-2020</p>\r\n\r\n	<ul>\r\n		<li>Використані технології: Android, Kotlin</li>\r\n		<li>Розробка функціональності, UI/UX дизайн</li>\r\n	</ul>\r\n	</li>\r\n</ul>\r\n\r\n<p><strong>Сертифікати:</strong></p>\r\n\r\n<ul>\r\n	<li>Сертифікат &quot;Java Programming&quot;, Oracle, 2019</li>\r\n	<li>Сертифікат &quot;Python for Data Science&quot;, Coursera, 2020</li>\r\n</ul>\r\n\r\n<p><strong>Рекомендації:</strong></p>\r\n\r\n<blockquote>\r\n<p>&quot;Іван Іванович Іваненко є відмінним викладачем з високим рівнем професійної компетенції та педагогічних навичок. Він завжди ставить інтереси студентів на перше місце та надає їм необхідну підтримку та допомогу. Рекомендую його як викладача програмування з безсумнівною впевненістю.&quot; -&nbsp;Олександр Петров, професор комп&#39;ютерних наук, &quot;Назва університету&quot;</p>\r\n</blockquote>\r\n', 1677939784, 1677939784);

-- --------------------------------------------------------

--
-- Структура таблицы `timetable`
--

CREATE TABLE `timetable` (
  `id` int(11) NOT NULL COMMENT 'ID',
  `lesson_id` int(11) NOT NULL COMMENT 'Заняття',
  `class_id` int(11) NOT NULL COMMENT 'Клас',
  `start` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Початок заняття',
  `end` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Кінець заняття',
  `status` enum('Новий','Завершено') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Новий' COMMENT 'Статус',
  `created_at` int(11) NOT NULL COMMENT 'Створено',
  `updated_at` int(11) NOT NULL COMMENT 'Відредаговано'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `timetable`
--

INSERT INTO `timetable` (`id`, `lesson_id`, `class_id`, `start`, `end`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '2023.03.13 16:00', '2023-03-13 17:20', 'Новий', 1678285000, 1678287252),
(2, 2, 1, '2023-03-15 16:00', '2023-03-15 17:20', 'Новий', 1678285658, 1678285658),
(3, 3, 1, '2023-03-17 16:00', '2023-03-17 17:20', 'Новий', 1678286201, 1678286201),
(4, 4, 1, '2023.03.20 16:00', '2023-03-20 17:20', 'Новий', 1678286233, 1678286233),
(5, 5, 1, '2023-03-22 16:00', '2023-03-22 17:20', 'Новий', 1678286260, 1678286260),
(6, 6, 1, '2023-03-24 16:00', '2023-03-24 17:20', 'Новий', 1678286296, 1678286296),
(7, 7, 1, '2023-03-27 16:00', '2023-03-27 17:20', 'Новий', 1678286368, 1678286368),
(8, 1, 2, '2023-03-14 16:00', '2023-03-14 17:20', 'Новий', 1678286443, 1678286443),
(9, 2, 2, '2023-03-16 16:00', '2023-03-16 17:20', 'Новий', 1678286463, 1678286463),
(10, 3, 2, '2023-03-18 16:00', '2023-03-18 17:20', 'Новий', 1678286486, 1678286486),
(11, 4, 2, '2023-03-21 16:00', '2023-03-21 17:20', 'Новий', 1678286504, 1678286504),
(12, 5, 2, '2023-03-23 16:00', '2023-03-23 17:20', 'Новий', 1678286538, 1678286538),
(13, 6, 2, '2023-03-25 16:00', '2023-03-25 17:20', 'Новий', 1678286562, 1678286562),
(14, 7, 2, '2023-03-28 16:00', '2023-03-28 17:20', 'Новий', 1678286586, 1678286586),
(15, 1, 3, '2023-03-13 13:00', '2023-03-13 14:20', 'Новий', 1678286657, 1678286657),
(16, 2, 3, '2023-03-15 13:00', '2023-03-15 14:20', 'Новий', 1678286687, 1678286687),
(17, 3, 3, '2023-03-17 13:00', '2023-03-17 14:20', 'Новий', 1678286707, 1678286707),
(18, 4, 3, '2023-03-20 13:00', '2023-03-20 14:20', 'Новий', 1678286749, 1678286749),
(19, 5, 3, '2023-03-22 13:00', '2023-03-22 14:20', 'Новий', 1678286774, 1678286774),
(20, 6, 3, '2023-03-24 13:00', '2023-03-24 14:20', 'Новий', 1678286907, 1678286907),
(21, 7, 3, '2023-03-27 13:00', '2023-03-27 14:20', 'Новий', 1678286924, 1678286924);

-- --------------------------------------------------------

--
-- Структура таблицы `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL COMMENT 'ID',
  `auth_key` varchar(32) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Ключ авторизації',
  `password_hash` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Пароль',
  `password_reset_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Токен скидування пароля',
  `email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'E-mail',
  `first_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Імʼя',
  `middle_name` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Прізвище',
  `last_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT 'По батькові',
  `status` smallint(6) NOT NULL DEFAULT '0' COMMENT 'Статус'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `user`
--

INSERT INTO `user` (`id`, `auth_key`, `password_hash`, `password_reset_token`, `email`, `first_name`, `middle_name`, `last_name`, `status`) VALUES
(1, '1u76VpEBt7OP5e5mijMD8N6ZpdXGrT_k', '$2y$13$66V6dtmakTDJEFTezZW9/eW5dPtpAmizGGBhKd33wzL9tHvBw.t3G', NULL, 'admin@courses.local', 'Адмін', 'Адмін', 'Адмін', 10),
(2, 'Sx5i_U9u1jEOD9zYSYHbeJ38IuaRooPS', '$2y$13$Tz4QuGNSTkmcSp1a2Yk6AeG4jpX43MbiYOGgUHvHCA1trBtweZ/Gy', NULL, 'melnik@courses.local', 'Богдан', 'Васильович', 'Мельник', 10),
(3, 'j8vzWCvsQtO5LMWG9xAK3YYgOb5WYVnc', '$2y$13$WZ0IK4JTdvURv3x9imdel.ZAD5GJdWFDqMXrngOseQWcXUhioWYGq', NULL, 'bondarenko@courses.local', 'Андрій', 'Миколайович', 'Бондаренко', 10);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `auth_assignment`
--
ALTER TABLE `auth_assignment`
  ADD PRIMARY KEY (`item_name`,`user_id`),
  ADD KEY `idx-auth_assignment-user_id` (`user_id`);

--
-- Индексы таблицы `auth_item`
--
ALTER TABLE `auth_item`
  ADD PRIMARY KEY (`name`),
  ADD KEY `rule_name` (`rule_name`),
  ADD KEY `idx-auth_item-type` (`type`);

--
-- Индексы таблицы `auth_item_child`
--
ALTER TABLE `auth_item_child`
  ADD PRIMARY KEY (`parent`,`child`),
  ADD KEY `child` (`child`);

--
-- Индексы таблицы `auth_rule`
--
ALTER TABLE `auth_rule`
  ADD PRIMARY KEY (`name`);

--
-- Индексы таблицы `classes`
--
ALTER TABLE `classes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `course_id` (`course_id`);

--
-- Индексы таблицы `class_students`
--
ALTER TABLE `class_students`
  ADD PRIMARY KEY (`id`),
  ADD KEY `class_id` (`class_id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `course_id` (`course_id`);

--
-- Индексы таблицы `content`
--
ALTER TABLE `content`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `url` (`url`);

--
-- Индексы таблицы `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `teacher_id` (`teacher_id`);

--
-- Индексы таблицы `favorites`
--
ALTER TABLE `favorites`
  ADD PRIMARY KEY (`id`),
  ADD KEY `course_id` (`course_id`),
  ADD KEY `student_id` (`student_id`);

--
-- Индексы таблицы `forum`
--
ALTER TABLE `forum`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `course_id` (`course_id`);

--
-- Индексы таблицы `homework`
--
ALTER TABLE `homework`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `lesson_id` (`lesson_id`);

--
-- Индексы таблицы `lessons`
--
ALTER TABLE `lessons`
  ADD PRIMARY KEY (`id`),
  ADD KEY `course_id` (`course_id`);

--
-- Индексы таблицы `marks`
--
ALTER TABLE `marks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `lesson_id` (`lesson_id`);

--
-- Индексы таблицы `media`
--
ALTER TABLE `media`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lesson_id` (`lesson_id`);

--
-- Индексы таблицы `teacher_info`
--
ALTER TABLE `teacher_info`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `teacher_id` (`teacher_id`);

--
-- Индексы таблицы `timetable`
--
ALTER TABLE `timetable`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lesson_id` (`lesson_id`),
  ADD KEY `class_id` (`class_id`);

--
-- Индексы таблицы `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `password_reset_token` (`password_reset_token`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `classes`
--
ALTER TABLE `classes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID', AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `class_students`
--
ALTER TABLE `class_students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID', AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT для таблицы `content`
--
ALTER TABLE `content`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID', AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `courses`
--
ALTER TABLE `courses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID', AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT для таблицы `favorites`
--
ALTER TABLE `favorites`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID', AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT для таблицы `forum`
--
ALTER TABLE `forum`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID', AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `homework`
--
ALTER TABLE `homework`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID', AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT для таблицы `lessons`
--
ALTER TABLE `lessons`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID', AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT для таблицы `marks`
--
ALTER TABLE `marks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID';

--
-- AUTO_INCREMENT для таблицы `media`
--
ALTER TABLE `media`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID', AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT для таблицы `teacher_info`
--
ALTER TABLE `teacher_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID', AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT для таблицы `timetable`
--
ALTER TABLE `timetable`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID', AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT для таблицы `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID', AUTO_INCREMENT=4;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `auth_assignment`
--
ALTER TABLE `auth_assignment`
  ADD CONSTRAINT `auth_assignment_ibfk_1` FOREIGN KEY (`item_name`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `auth_assignment_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Ограничения внешнего ключа таблицы `auth_item`
--
ALTER TABLE `auth_item`
  ADD CONSTRAINT `auth_item_ibfk_1` FOREIGN KEY (`rule_name`) REFERENCES `auth_rule` (`name`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `auth_item_child`
--
ALTER TABLE `auth_item_child`
  ADD CONSTRAINT `auth_item_child_ibfk_1` FOREIGN KEY (`parent`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `auth_item_child_ibfk_2` FOREIGN KEY (`child`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `classes`
--
ALTER TABLE `classes`
  ADD CONSTRAINT `classes_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Ограничения внешнего ключа таблицы `class_students`
--
ALTER TABLE `class_students`
  ADD CONSTRAINT `class_students_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `class_students_ibfk_2` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `class_students_ibfk_3` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Ограничения внешнего ключа таблицы `courses`
--
ALTER TABLE `courses`
  ADD CONSTRAINT `courses_ibfk_1` FOREIGN KEY (`teacher_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Ограничения внешнего ключа таблицы `favorites`
--
ALTER TABLE `favorites`
  ADD CONSTRAINT `favorites_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `favorites_ibfk_2` FOREIGN KEY (`student_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Ограничения внешнего ключа таблицы `forum`
--
ALTER TABLE `forum`
  ADD CONSTRAINT `forum_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `forum_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Ограничения внешнего ключа таблицы `homework`
--
ALTER TABLE `homework`
  ADD CONSTRAINT `homework_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `homework_ibfk_2` FOREIGN KEY (`lesson_id`) REFERENCES `lessons` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Ограничения внешнего ключа таблицы `lessons`
--
ALTER TABLE `lessons`
  ADD CONSTRAINT `lessons_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Ограничения внешнего ключа таблицы `marks`
--
ALTER TABLE `marks`
  ADD CONSTRAINT `marks_ibfk_1` FOREIGN KEY (`lesson_id`) REFERENCES `lessons` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `marks_ibfk_2` FOREIGN KEY (`student_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Ограничения внешнего ключа таблицы `media`
--
ALTER TABLE `media`
  ADD CONSTRAINT `media_ibfk_1` FOREIGN KEY (`lesson_id`) REFERENCES `lessons` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Ограничения внешнего ключа таблицы `teacher_info`
--
ALTER TABLE `teacher_info`
  ADD CONSTRAINT `teacher_info_ibfk_1` FOREIGN KEY (`teacher_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Ограничения внешнего ключа таблицы `timetable`
--
ALTER TABLE `timetable`
  ADD CONSTRAINT `timetable_ibfk_1` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `timetable_ibfk_2` FOREIGN KEY (`lesson_id`) REFERENCES `lessons` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
