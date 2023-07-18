import random


def rando_title(words):
    """Select 1 to 6 random words"""
    title = ""
    for i in range(random.randint(1, 6)):
        title += " " + random.choice(words)
    return title


filename = "stream_titles.txt"
words = []

with open(filename, "r") as sentences:
    for line in sentences:
        word_list = line.split(" ")
        for word1 in word_list:
            if word1 not in words and word1 != "\n":
                words.append(word1.strip())

for i in range(1):
    wo = rando_title(words)
    print(wo)
