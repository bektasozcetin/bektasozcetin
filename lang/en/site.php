<?php

return [
    'meta' => [
        'og_title' => 'Solid Laravel backends, carefully crafted interfaces',
        'title' => 'Bektaş Özçetin — Full Stack Laravel Developer',
        'description' => 'Full stack developer building end-to-end web apps, online shops, admin panels and REST APIs with Laravel and Tailwind CSS.',
    ],

    'skip' => 'Skip to content',
    'nav' => [
        'notes' => 'Notes', 'work' => 'Work', 'services' => 'Services', 'skills' => 'Skills', 'about' => 'About', 'faq' => 'FAQ',
        'code' => 'Code', 'models' => 'Ways to work', 'contact' => 'Contact',
        'quick' => 'Quick jump', 'menu' => 'Menu', 'menu_label' => 'Main menu', 'close_menu' => 'Close menu', 'lang' => 'Language',
        'to_dark' => 'Switch to dark theme', 'to_light' => 'Switch to light theme',
    ],
    'cta' => 'Book a call',

    'cover' => [
        'issue' => 'Issue 01 · November 2026',
        'available' => 'Open to new projects',
        'portrait_alt' => 'Black and white portrait of Bektaş Özçetin',
        'lede_a' => 'I build solid Laravel backends and fast, carefully crafted Tailwind CSS interfaces for businesses. From database to deploy,',
        'lede_em' => 'end to end',
        'lede_b' => ', in one pair of hands.',
        'cv' => 'Résumé (PDF) ↓',
        'in_issue' => 'In this issue',
        'lines' => [
            ['id' => 'projeler', 'n' => '01', 't' => ':count case studies', 's' => 'Problem, decision, result'],
            ['id' => 'yetkinlikler', 'n' => '03', 't' => 'Craft at every layer', 's' => 'Backend, API, interface, DevOps'],
            ['id' => 'kod', 'n' => '04', 't' => 'Code showcase', 's' => 'Action · Livewire · Pest · Migration'],
            ['id' => 'calisma', 'n' => '08', 't' => 'Projects, care, consulting', 's' => 'Models with clear scope and price'],
            ['id' => 'iletisim', 'n' => '12', 't' => 'Book a call', 's' => 'Türkiye time now '],
        ],
    ],

    'brands' => ['label' => 'Brands I\'ve worked with', 'count' => ':count brands over the last 6 years'],

    'projects' => [
        'forks' => 'forks', 'updated' => 'updated :time', 'from_github' => 'Live from GitHub',
        'eyebrow' => '01 · Case studies', 'title_a' => 'Selected', 'title_b' => 'work',
        'lede' => 'For each project I explain the problem, the technical decisions I made and the result. Open one to read the full story.',
        'filter_label' => 'Filter projects',
        'filters' => ['all' => 'All', 'web' => 'Web apps', 'panel' => 'Admin panels', 'api' => 'API'],
        'sample' => 'Sample data · for testing, replace with real projects before launch',
        'read_case' => 'Read the case study', 'full_page' => 'Open full page ↗', 'live_demo' => 'Live demo ↗', 'code' => 'Code ↗', 'result' => 'Result',
        'os_a' => 'Open source', 'os_b' => '& packages', 'all_repos' => 'All GitHub repositories ↗', 'stars' => 'stars',
    ],
    'case' => [
        'gallery' => 'Screens', 'show_shot' => 'Show screen :n', 'next' => 'Next project', 'next_short' => 'Next project →',
        'label' => 'Case study', 'role' => 'My role', 'duration' => 'Duration', 'stack' => 'Stack',
        'problem' => 'Problem', 'approach' => 'Approach', 'lesson' => 'What I\'d do differently today', 'decisions' => 'Technical decisions',
        'close' => 'Close case study', 'shot' => '[Screenshot · 16:10 · at least 1600 px]', 'back' => '← All work',
    ],
    'band' => [
        'label' => 'Call invitation', 'title' => 'Planning something similar?', 'sub' => 'In our first call we\'ll pin down the scope and timeline together.',
    ],

    'services' => [
        'eyebrow' => '02 · What I do', 'title_a' => 'From idea', 'title_b' => 'to launch',
        'lede' => 'A new product, a growing shop or a Laravel app nobody has upgraded in years. Each has a clear path.',
    ],

    'skills' => [
        'stack_a' => 'Tech', 'stack_b' => 'stack', 'stack_lede' => 'The tools I use every day, and how deeply I know them.',
        'years' => ':n yrs', 'groups' => ['backend' => 'Backend', 'frontend' => 'Frontend', 'data' => 'Data', 'devops' => 'DevOps', 'tools' => 'Tools'],
        'legend' => 'Core expertise', 'level' => 'Level',
        'eyebrow' => '03 · Skills', 'title_a' => 'Craft at', 'title_b' => 'every layer',
        'lede' => 'Tested, maintainable code that runs in production. From backend to interface, database to server.',
        'core' => 'Core expertise', 'db' => 'Database',
        'backend' => ['Eloquent ORM & relationships', 'Queues, jobs & Horizon', 'Event / listener architecture', 'Policies, gates & roles', 'Sanctum authentication', 'Service & action layers', 'Pest / PHPUnit tests', 'Payment integrations'],
    ],

    'code' => [
        'eyebrow' => '04 · Code showcase', 'title_a' => 'How I', 'title_b' => 'write code',
        'lede' => 'Short examples of patterns from my real projects. Pick a file and read why it\'s written that way.',
        'label' => 'Choose a code sample', 'why' => 'Why this way?',
        'samples' => [
            'action' => ['title' => 'One job, one class', 'notes' => [
                'Business logic lives in a testable Action class, not the controller.',
                'A transaction writes the order and stock together, or not at all.',
                'afterCommit queues the payment only once the database write is final.',
            ]],
            'livewire' => ['title' => 'Validation next to the data', 'notes' => [
                'Rules sit on the properties, so the form and its validation read together.',
                'Privacy consent is required with the \'accepted\' rule.',
                'The email is queued so the visitor never waits.',
            ]],
            'pest' => ['title' => 'Test the behaviour', 'notes' => [
                'The test describes what the app does: stock is reserved, payment is queued.',
                'Queue::fake verifies without calling the outside service.',
                'Factories give readable, repeatable data.',
            ]],
            'migration' => ['title' => 'The schema comes first', 'notes' => [
                'Foreign keys and delete behaviour are enforced by the database.',
                'The frequently filtered \'status\' column is indexed.',
                'Amounts are stored as integer cents: no floating-point errors.',
            ]],
        ],
    ],

    'principles' => [
        'eyebrow' => '05 · Principles', 'title_a' => 'Five rules,', 'title_b' => 'every project',
        'lede' => 'Technology changes. These rules don\'t.',
        'items' => [
            ['Data model first', 'A well-built schema saves hundreds of lines later. Every project starts with tables and relationships.'],
            ['No delivery without tests', 'Every critical flow (payments, orders, permissions) is protected by automated tests.'],
            ['Simple wins', 'Start with the simplest solution. Add complexity only when it\'s truly needed.'],
            ['Readable by anyone', 'Code is written for the next developer: clear names, small classes, documented decisions.'],
            ['You own it', 'Code, servers and every credential stay with you. You never depend on me.'],
        ],
    ],

    'about' => [
        'eyebrow' => '06 · About', 'title_a' => 'I enjoy', 'title_b' => 'solving problems', 'title_c' => 'more than writing code.',
        'photo_alt' => 'A desk in blue light with code open on a laptop',
        'photo_caption' => 'My desk · :city',
        'p1' => 'Hi, I\'m Bektaş. I\'m a full stack developer in the Laravel ecosystem. I start a project with the database schema, continue with the API and interface, and finish with tests and automated deploys.',
        'p2' => 'I care about clean, readable, tested code. When I turn a design into Tailwind CSS, every screen has to work just as well on a phone.',
        'id_rows' => [
            ['Focus', 'Laravel + Tailwind CSS'],
            ['Work', 'Freelance · Full-time'],
            ['Location', ':city · Remote'],
            ['Languages', 'Turkish · English (B2)'],
            ['Status', 'Open to new projects'],
        ],
        'values' => [
            ['Clean code', 'SOLID principles, readable and testable structure.'],
            ['Performance', 'Fast apps through query tuning, caching and queues.'],
            ['Clear process', 'Regular updates, a clear timeline and open communication.'],
        ],
    ],

    'experience' => [
        'eyebrow' => '07 · Experience', 'title_a' => 'Career', 'title_b' => 'path',
        'lede' => 'Where I\'ve worked, my education and certificates.', 'edu_title' => 'Education & certificates',
    ],

    'models' => [
        'eyebrow' => '08 · Ways to work', 'title_a' => 'How we can', 'title_b' => 'work together',
        'lede' => 'Clear scope, price known up front. Pick the model that fits and we\'ll cover the rest on a call.',
        'process_a' => 'Process', 'process_b' => '· six steps from idea to launch',
        'steps' => [
            ['Discovery', 'Clarify needs, users and goals.'],
            ['Database', 'Tables, relationships and migration plan.'],
            ['Backend & API', 'Business logic, services and endpoints.'],
            ['Interface', 'Responsive screens with Tailwind and Livewire.'],
            ['Test', 'Feature and unit tests with Pest.'],
            ['Deploy', 'Release with CI/CD, then monitor.'],
        ],
    ],

    'refs' => ['eyebrow' => '09 · References', 'title_a' => 'What people I\'ve', 'title_b' => 'worked with say', 'photo' => 'photo'],

    'faq' => [
        'eyebrow' => '11 · FAQ', 'title_a' => 'Frequently asked', 'title_b' => 'questions',
        'lede' => 'If your question isn\'t here, just write to me.',
    ],

    'contact' => [
        'or_calendar' => 'Rather talk than type?', 'calendar_hint' => 'Pick a time on my calendar, free :min-minute call',
        'eyebrow' => '12 · Contact', 'title_a' => 'Have a project?', 'title_b' => 'Let\'s talk.',
        'clock' => 'Türkiye time', 'reply' => 'I usually reply within :time',
        'email' => 'Email', 'copy' => 'Copy email address', 'copied' => 'Email address copied',
        'copy_fail' => 'Couldn\'t copy. You can select the address manually.',
        'form_label' => 'Call request form', 'name' => 'Your name', 'name_ph' => 'Full name', 'mail_ph' => 'name@company.com',
        'type' => 'Project type', 'message' => 'Message', 'message_ph' => 'What do you want to build, and by when?',
        'types' => ['web' => 'Web app', 'shop' => 'E-commerce', 'admin' => 'Admin panel', 'api' => 'API', 'care' => 'Maintenance', 'consult' => 'Consulting'],
        'kvkk_a' => 'I have read the', 'kvkk_link' => 'privacy notice', 'kvkk_b' => 'about how my data is used to reply to this request.',
        'submit' => 'Request a call', 'sending' => 'Sending…',
        'privacy' => 'I only use your details to reply to you and never share them.',
        'sent_title' => 'Request received', 'sent_sub' => 'Thanks! I\'ll email you a few times that work for a call.', 'new' => 'Start a new request',
        'error' => 'Something went wrong. Could you try again in a moment?',
        'errors' => [
            'name' => 'Please add your name so I know who I\'m writing to.',
            'email' => 'Enter a valid email address. That\'s where I\'ll reply.',
            'message' => 'Tell me about your project in a few sentences (at least 10 characters).',
            'kvkk' => 'Please confirm the privacy notice to send.',
        ],
    ],

    'notes' => [
        'eyebrow' => '10 · Notes', 'eyebrow_plain' => 'Notes', 'title_a' => 'Notes on', 'title_b' => 'Laravel',
        'lede' => 'I turn the problems I run into on projects, and how I solved them, into short, practical write-ups.',
        'index_lede' => 'Field notes on Laravel, databases, performance and running projects. Each one started with a real problem on a real project.',
        'all' => 'All notes', 'read' => 'Read the note', 'min' => ':min min read', 'kicker' => 'Note · :min min read',
        'og_title' => 'Short, practical notes on Laravel', 'empty' => 'No notes with this tag yet.',
        'all_tags' => 'All', 'tag_label' => 'Filter by tag', 'back' => '← All notes',
        'newer' => 'Newer', 'older' => 'Older', 'share' => 'Share', 'copy_link' => 'Copy link', 'link_copied' => 'Link copied',
        'author_role' => 'Full Stack Laravel Developer', 'cta_title' => 'Need a hand with this?', 'cta_sub' => "Let's look at your project together; the first call is free.",
        'count' => ':count notes',
    ],

    'booking' => [
        'label' => 'Booking calendar', 'title' => 'Book a call', 'sub' => 'Free :min-minute intro call · Google Meet',
        'step_day' => 'Pick a day', 'step_time' => 'Pick a time', 'step_info' => 'Your details', 'tz' => 'Türkiye time (GMT+3)',
        'loading' => 'Loading available times…', 'load_error' => "Couldn't load the calendar. You can use the contact form instead.",
        'no_slots' => 'No free times in the coming days. You can use the contact form instead.', 'full' => 'taken',
        'selected' => 'Selected time', 'change' => 'Change', 'topic' => 'Topic', 'note' => 'Your project in a few words (optional)',
        'note_ph' => 'What are you building? A link or a short note is plenty.',
        'submit' => 'Request the call', 'sending' => 'Sending…',
        'done_title' => 'Request received', 'done_sub' => "Got your request for :date. Once I confirm, I'll email you the meeting link.",
        'add_cal' => 'Add to my calendar (.ics)', 'close' => 'Close', 'or_form' => 'Prefer writing? Contact form →',
        'ics_title' => 'Call · Bektaş Özçetin', 'free' => 'free slots',
        'errors' => ['slot' => 'Pick a day and a time first.', 'taken' => 'That time was just taken. Could you pick another one?'],
    ],

    'consent' => [
        'text' => "I'd like to use Google Analytics to keep anonymous visit statistics and improve the site. Do you allow analytics cookies?",
        'accept' => 'Allow', 'decline' => 'Decline', 'more' => 'Details',
    ],

    'footer' => ['made' => 'Built with Laravel + Tailwind CSS', 'privacy' => 'Privacy notice', 'top' => 'Back to top ↑'],

    'palette' => [
        'cv' => 'Download CV (PDF)', 'notes_page' => 'All notes', 'book' => 'Pick a time',
        'search' => 'Search sections or actions…', 'move' => 'move', 'select' => 'select', 'close' => 'close',
        'section' => 'Section', 'action' => 'Action', 'empty' => 'No results for “:q”. Try another word.',
        'to_contact' => 'Go to the contact form', 'switch_lang' => 'Türkçe’ye geç',
        'sections' => [
            ['projeler', 'Work', 'Case studies', 'P'],
            ['hizmetler', 'Services', 'What I do', 'S'],
            ['yetkinlikler', 'Skills', 'Backend, API, interface, DevOps', 'K'],
            ['kod', 'Code showcase', 'Action · Livewire · Pest', '{'],
            ['ilkeler', 'Principles', 'Five rules', 'R'],
            ['hakkimda', 'About', 'ID card and approach', 'B'],
            ['deneyim', 'Experience', 'Career, education', 'E'],
            ['calisma', 'Ways to work', 'Projects, care, consulting', 'W'],
            ['referanslar', 'References', 'People I\'ve worked with', 'Q'],
            ['notlar', 'Notes', 'Writing on Laravel', 'N'],
            ['sss', 'FAQ', 'Common questions', '?'],
            ['iletisim', 'Contact', 'Form and email', '@'],
        ],
    ],

    'privacy' => [
        'title' => 'Privacy Notice',
        'body' => 'This notice is provided by Bektaş Özçetin as data controller under Turkey\'s Personal Data Protection Law (KVKK, No. 6698). The name, email address, message and meeting time you share through the contact form or booking calendar are processed only to reply to your request, are never shared with third parties and are kept for at most 12 months. To exercise your rights under Article 11 of the law, write to :email. The site counts visits as plain numbers without storing personal data; if Google Analytics is enabled, analytics cookies only run with your consent.',
    ],
];
