#!/usr/bin/env php
<?php

/**
 * Advanced Dataset Augmentation Script
 * Generates 3 natural, varied rewritings of each input while preserving output
 */

$inputFile = __DIR__ . '/coach_finetuning_dataset.jsonl';
$outputFile = __DIR__ . '/coach_finetuning_dataset_augmented.jsonl';

// Manual rewrites for natural variations
$rewrites = [
    // Course Creation (Lines 1-5)
    "Where do I go to create a new course?" => [
        "What's the link to create a course?",
        "I need to set up a new course - where's that option?",
        "Point me to the course creation form"
    ],
    "How can I add a course?" => [
        "Where's the add course button?",
        "What's the process for adding a new course?",
        "I want to publish a new course, how do I do that?"
    ],
    "Where is the page to create courses?" => [
        "Can you show me where to build a new course?",
        "Which page lets me create courses?",
        "Where do I find the new course option?"
    ],
    "I want to create a new course, where should I go?" => [
        "Help me create a course - where do I start?",
        "What page do I need to create a new course?",
        "Where's the course builder located?"
    ],
    "How do I set up a new course?" => [
        "What are the steps to set up a course?",
        "Guide me to the new course setup page",
        "I'm ready to create a course, where do I begin?"
    ],
    
    // View Courses (Lines 6-10)
    "Where can I see all my courses?" => [
        "Show me my complete course list",
        "How do I access all the courses I've made?",
        "Where's my courses overview page?"
    ],
    "How do I view my course list?" => [
        "I need to see all my courses",
        "Where can I find my course catalog?",
        "What page shows all my created courses?"
    ],
    "Where is my courses page?" => [
        "How do I get to my courses overview?",
        "Can you direct me to my course listings?",
        "Where do I find all my published courses?"
    ],
    "Show me all my courses" => [
        "I want to see my entire course collection",
        "Display my full list of courses",
        "Where can I browse through my courses?"
    ],
    "Where can I manage my courses?" => [
        "What page lets me handle my courses?",
        "I need to organize my courses - where do I go?",
        "How do I access my course management panel?"
    ],
    
    // Edit Courses (Lines 11-14)
    "How do I edit a course?" => [
        "What's the process for modifying a course?",
        "I need to update a course - where do I start?",
        "Where can I make changes to an existing course?"
    ],
    "Where can I update my course information?" => [
        "How do I change course details?",
        "I want to modify my course content - where should I go?",
        "What page allows me to edit course info?"
    ],
    "I need to modify a course, where do I go?" => [
        "Where's the course editing interface?",
        "How can I update one of my courses?",
        "Point me to where I can change course details"
    ],
    "How can I change course details?" => [
        "What's the way to edit course information?",
        "I want to revise my course - where do I do that?",
        "Where do I update the course content?"
    ],
    
    // Sections (Lines 15-19)
    "Where do I add sections to my course?" => [
        "How can I create course modules?",
        "I need to add sections - where's that feature?",
        "What page lets me organize course sections?"
    ],
    "How can I create course sections?" => [
        "Where do I set up modules for my course?",
        "I want to add sections to organize content",
        "What's the process for creating course sections?"
    ],
    "Where is the sections page?" => [
        "How do I access the sections manager?",
        "I need to find the sections interface",
        "Where can I build out course sections?"
    ],
    "I want to add lessons to my course, where do I go?" => [
        "How do I create lessons within my course?",
        "Where's the lesson creation page?",
        "I need to add lesson content - where should I start?"
    ],
    "How do I organize my course content?" => [
        "What's the best way to structure my course?",
        "Where can I arrange my course materials?",
        "I need to organize lessons and sections - help me"
    ],
    
    // Messages/Inbox (Lines 20-24)
    "Where can I check my messages?" => [
        "How do I read my inbox?",
        "I need to see my messages - where are they?",
        "What page shows my student messages?"
    ],
    "How do I read my inbox?" => [
        "Where's my message center?",
        "I want to check new messages",
        "Show me where to view communications"
    ],
    "Where are my messages?" => [
        "How do I find my inbox?",
        "I'm looking for student messages",
        "What page has my message history?"
    ],
    "I want to see my messages" => [
        "Where can I view all communications?",
        "How do I access my message inbox?",
        "Show me my conversation history"
    ],
    "How can I communicate with students?" => [
        "What's the messaging system for students?",
        "I need to send a message to a student",
        "Where do I chat with my learners?"
    ],
    
    // Profile/Account (Lines 25-29)
    "Where is my profile page?" => [
        "How do I access my account settings?",
        "I need to find my profile - where is it?",
        "Show me where to view my user info"
    ],
    "How do I update my profile?" => [
        "Where can I edit my account details?",
        "I want to change my profile information",
        "What page lets me modify my profile?"
    ],
    "Where can I change my account details?" => [
        "How do I edit my personal information?",
        "I need to update my user settings",
        "Where's the account management page?"
    ],
    "I need to edit my profile information" => [
        "How can I modify my account details?",
        "Where do I update my user profile?",
        "I want to change my personal info - where do I go?"
    ],
    "Where do I manage my account settings?" => [
        "How do I access account preferences?",
        "I need to configure my profile settings",
        "What page handles account management?"
    ],
    
    // Dashboard (Lines 30-34)
    "Where is my dashboard?" => [
        "How do I get to my main overview page?",
        "I need to see my coaching dashboard",
        "Show me my control panel"
    ],
    "How do I get to my main page?" => [
        "Where's my home dashboard?",
        "I want to view my overview page",
        "Take me to my main coaching interface"
    ],
    "Where can I see my overview?" => [
        "How do I access my dashboard summary?",
        "I need my activity overview",
        "Show me my coaching statistics page"
    ],
    "Show me my dashboard" => [
        "I want to see my main page",
        "Where's my overview panel?",
        "Take me to my coaching hub"
    ],
    "I want to go to my home page" => [
        "How do I return to the dashboard?",
        "Where's the main coaching page?",
        "Take me back to my homepage"
    ],
    
    // Delete Course (Lines 35-37)
    "How do I delete a course?" => [
        "What's the process for removing a course?",
        "I need to delete a course - where do I do that?",
        "Where can I remove an old course?"
    ],
    "Where can I remove a course?" => [
        "How do I get rid of a course I don't need?",
        "I want to delete a course from my list",
        "What's the way to remove courses?"
    ],
    "I want to delete one of my courses" => [
        "How can I remove a specific course?",
        "I need to get rid of an old course",
        "Where do I delete courses I no longer offer?"
    ],
    
    // View Course Details (Lines 38-40)
    "How can I view a specific course?" => [
        "Where do I see details for one particular course?",
        "I need to check a specific course's information",
        "How do I open an individual course?"
    ],
    "Where can I see course details?" => [
        "How do I view information about a course?",
        "I want to check the details of my course",
        "Show me where to see course specifics"
    ],
    "I want to check my course information" => [
        "How can I review my course details?",
        "Where do I see what's in my course?",
        "I need to look at course specs"
    ],
    
    // Students (Lines 41-43)
    "Where do I go to see my students?" => [
        "How can I view enrolled learners?",
        "I need to check who's taking my courses",
        "Where's the student roster?"
    ],
    "How can I manage my students?" => [
        "What page shows my enrolled students?",
        "I need to see who's learning from me",
        "Where do I access student information?"
    ],
    "Where is the student list?" => [
        "How do I view all my learners?",
        "I need to see enrollment data",
        "Show me who's enrolled in my courses"
    ],
    
    // Videos (Lines 44-46)
    "How do I add videos to my course?" => [
        "What's the process for uploading course videos?",
        "I need to include video content - how?",
        "Where can I insert videos into lessons?"
    ],
    "Where can I upload course videos?" => [
        "How do I add video files to my course?",
        "I want to include video lessons",
        "What's the video upload process?"
    ],
    "I want to add video content to my course" => [
        "How can I upload videos for my lessons?",
        "I need to include multimedia - where do I start?",
        "Where do I add video materials?"
    ],
    
    // Course Structure (Lines 47-48)
    "How do I structure my course content?" => [
        "What's the best way to organize course materials?",
        "I need help arranging my course layout",
        "Where can I set up my course structure?"
    ],
    "Where can I organize my course modules?" => [
        "How do I arrange sections and lessons?",
        "I want to structure my course modules",
        "What page lets me organize course content?"
    ],
];

// Read original dataset
$lines = file($inputFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$augmentedData = [];

echo "🔄 Processing dataset...\n\n";

foreach ($lines as $lineNum => $line) {
    $data = json_decode($line, true);
    
    if (!$data || !isset($data['input']) || !isset($data['output'])) {
        continue;
    }
    
    $originalInput = $data['input'];
    $output = $data['output'];
    
    // Get variations
    if (isset($rewrites[$originalInput])) {
        $variations = $rewrites[$originalInput];
    } else {
        // Fallback: simple variations
        $variations = [
            str_replace('?', ' please?', $originalInput),
            ucfirst(strtolower($originalInput)),
            preg_replace('/^(Where|How)/', 'Tell me where', $originalInput)
        ];
    }
    
    // Create 3 entries
    foreach ($variations as $idx => $variation) {
        $augmentedData[] = [
            'input' => $variation,
            'output' => $output
        ];
    }
    
    echo "✓ Line " . ($lineNum + 1) . ": $originalInput\n";
}

// Write augmented dataset
$handle = fopen($outputFile, 'w');
foreach ($augmentedData as $entry) {
    fwrite($handle, json_encode($entry, JSON_UNESCAPED_SLASHES) . "\n");
}
fclose($handle);

echo "\n✅ Dataset augmentation complete!\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "📊 Original entries:   " . count($lines) . "\n";
echo "📊 Augmented entries:  " . count($augmentedData) . "\n";
echo "📊 Multiplication:     3x\n";
echo "📁 Output file:        coach_finetuning_dataset_augmented.jsonl\n";
