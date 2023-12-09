# StudentResumeManagement
Project I made in my senior year at Murray State University.

## Project Information
Instructor; Dr. Solomon Antony
Date: Fall 2023 Semester
Sponsor: Dr. Victor Raj
Client: Murray State University
Group Members: Kristopher Myers

## Problem
As a faculty member of Murray State University, Dr. Raj gets contacted by many employers asking
if there are students at MSU that meet certain requirements like having certain certain skills, 
having certain certifications, or graduating by a certain date to name a few. Going through 
and asking every student to send in their resume and then analyzing them to determine who has 
the skills an employer requires would be a long and tedious process. 

## Solution
So what Dr. Raj wanted is a tool that students can access and input all this information themselves,
and then a tool where faculty members can pull up this information and quickly search through it 
for the purposes of sending that information to the employer.

## Requirements
Using information from Dr. Raj, I created these four requirements for the project:
- The solution must allow students to enter various types of information into a database
- In doing so, a comprehensive database of student’s work-related skills will be created
- Search tools that allow searching through the database will need to be implemented 
- And then it should be possible to generate reports using student information

## The Database
So essentially, the project involved the development of various frontends for a database looking like this:
![image](https://github.com/KristopherMyers/StudentResumeManagement/assets/130585836/79904f58-860e-4b7d-9f7a-4e8a81b1af28)

## Pages

### Student Page
Allows student users, identified by their M# (the letter M followed by 8 digits that uniquely 
identifies each MSU student), to enter and edit their information within the database. This
information consists of:
- General Information
- Contact Information
- Skills Possessed
- Certifications Possessed
- Work History

### Faculty Page
Allows faculty members to search through the list of students using various search options, 
including:
- Major Names
- Certification Names
- Skills Possessed
Also allows faculty members to narrow down search with various inclusion rules, including:
- Require resulting students to match all search options
- Require resulting students to be seeking employment
- Require resulting students to have graduated by a certain date
Finally, allows faculty members to generate a PDF report of students that have been
selected using the checkbox on the very left side of their row.

### Admin Page
Allows administrators to add new skills to the list of possible skills, view all 
current skills, delete current skills, and delete individual student records.

## Known Issues
- Screen sizes other than 1920x1080 have not been tested
- Does not check for errors in phone number formatting
- Does not check for errors in date realisticity
- Faculty page does not keep student selection when applying and clearing search filters

## What Should be Added in the Future
- Increase security by connecting to MSU accounts
- Email notifications to students when information is updated
- Content filtering
- More precise moderation tools
- Confirmation for delete actions
- Make PDF look nicer/more presentable to employers
- Allow trusted employers to access the faculty view
