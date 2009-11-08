"""
**@This program is free software: you can redistribute it and/or modify
**@it under the terms of the GNU Lesser General Public License as published by
**@the Free Software Foundation, either version 3 of the License, or
**@(at your option) any later version.
**@This program is distributed in the hope that it will be useful,
**@but WITHOUT ANY WARRANTY; without even the implied warranty of
**@MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
**@GNU Lesser General Public License for more details.
**@You should have received a copy of the GNU Lesser General Public License
**@along with this program.  If not, see <http://www.gnu.org/licenses/>.

Copyright (C) 2008-2009 The SUIT Group.
http://www.suitframework.com/
http://www.suitframework.com/docs/credits
"""
import copy
import inspect
import os
import pprint
import sys

class SUITError(ValueError):
	pass

class SUIT:
	chain = []

	_evalstring = ''

	_filepath = ''

	config = []

	log = {
		'getTemplate': [],
		'replace': [],
		'parse': []
	}

	templates = ''

	vars = {}

	version = '0.0.0'

	def __init__(self, config):
		"""http://www.suitframework.com/docs/SUIT+Construct#howitworks"""
		try:
			self.config = config
			if not os.path.exists(self.config['templates']['code']) or not os.path.exists(self.config['templates']['content']) or not os.path.exists(self.config['templates']['glue']):
				raise SUITError('One of the template directories does not exist. See http://www.suitframework.com/docs/errors#error4')
		except TypeError:
			raise SUITError('Provided argument not array or improperly formatted one. See http://www.suitframework.com/docs/errors#error5')

	def _includeFile(self, content):
		suit = self
		suit.content = content
		execfile(self._filepath)
		return suit.content

	def _strpos(self, haystack, needle, offset = 0):
		haystack = str(haystack)
		needle = str(needle)
		offset = int(offset)
		return (haystack.find(needle, offset), haystack.upper().find(needle.upper(), offset))[self.config['flag']['insensitive']]

	def _walk(self, nodes, tree, replace, escape, key, match):
		walked = []
		for value in tree:
			if isinstance(value, list):
				value[1] = self._walk(nodes, value[1], replace, escape, key + 1, False)
				if not value[2][0][3]:
					value[1] = self.replace(replace, value[1], None, key + 1)
				node = False
				for value2 in nodes:
					if value[2][0][0] == value2[0]:
						node = value2
						break
				if node:
					self._evalstring = node[2]
					value[1] = (''.join((node[0], value[1], node[1])), self._walkEval(value[1], nodes, replace, escape, key + 2))[len(node) >= 3]
					walked.append(([value[0], value[1]], value[1])[not match])
			else:
				walked.append(value)
		if not match:
			walked = ''.join(walked)
		return walked

	def _walkEval(self, case, nodes, replace, escape, key):
		suit = self
		return eval(self._evalstring)

	def escape(self, symbols, escape, string):
		"""http://www.suitframework.com/docs/escape#howitworks"""
		escape = str(escape)
		string = str(string)
		symbols = copy.deepcopy(symbols)
		replace = []
		try:
			for value in symbols:
				replace.append([value, escape + value])
			pos = -1
			smallest = False
			while (smallest != -1):
				smallest = -1
				nonevalues = []
				for key, value in enumerate(symbols):
					symbolpos = self._strpos(string, value, pos + 1)
					if symbolpos != -1:
						if symbolpos < smallest or smallest == -1:
							smallest = symbolpos
					else:
						nonevalues.append(key)
				offset = 0
				for value in nonevalues:
					del symbols[value - offset]
					offset += 1
				pos = (len(string), smallest)[smallest != -1]
				if pos != -1:
					count = 0
					if escape:
						start = pos - len(escape)
						while abs(start) == start and string[start:start + len(escape)] == escape:
							count += len(escape)
							start = pos - count - len(escape)
						count = count / len(escape)
					string = ''.join((string[0:pos - (count * len(escape))], escape * (count * 2), string[pos:len(string)]))
					pos += count * len(escape)
		except TypeError:
			raise SUITError('Provided argument not array or improperly formatted one. See http://www.suitframework.com/docs/errors#error5')
		return self.replace(replace, string)

	def explodeUnescape(self, explode, escape, glue):
		"""http://www.suitframework.com/docs/explodeUnescape#howitworks"""
		array = []
		explode = str(explode)
		escape = str(escape)
		glue = str(glue)
		pos = -1
		temppos = True
		while temppos != -1:
			temppos = self._strpos(glue, explode, pos + 1)
			pos = (len(glue), temppos)[temppos != -1]
			if pos != -1:
				count = 0
				if escape:
					start = pos - len(escape)
					while abs(start) == start and glue[start:start + len(escape)] == escape:
						count += len(escape)
						start = pos - count - len(escape)
					count = count / len(escape)
				condition = count % 2
				if condition:
					count += 1
				if count:
					glue = glue[0:pos - ((count / 2) * len(escape))] + glue[pos:len(glue)]
					pos -= (count / 2) * len(escape)
				if not condition:
					array.append(glue[0:pos])
					glue = glue[pos + len(explode):len(glue)]
					pos = -1
		return array

	def getSection(self, string, content, open = None, close = None, end = None, escape = None, label = None):
		"""http://www.suitframework.com/docs/getSection#howitworks"""
		array = []
		string = str(string)
		content = str(content)
		open = str((self.config['parse']['open'], open)[open != None])
		close = str((self.config['parse']['close'], close)[close != None])
		end = str((self.config['parse']['end'], end)[end != None])
		escape = str((self.config['parse']['escape'], escape)[escape != None])
		nodes = [
			[''.join((open, string, close)), ''.join((open, end, string, close)), 'case']
		]
		parse = self.parse(nodes, content, [], escape, True, label)
		content = parse[1]
		for value in parse[0]:
			array.append(value[1])
		return (array, content)

	def getTemplate(self, template):
		"""http://www.suitframework.com/docs/getTemplate#howitworks"""
		template = str(template).replace('../', '')
		content = ''
		backtrace = inspect.stack()
		self.log['getTemplate'].append({
			'backtrace': backtrace[1],
			'code': [],
			'content': [False, False, False],
			'glue': [template]
		});
		key = len(self.log['getTemplate']) - 1
		if not template in self.chain:
			self._filepath = '%s/%s.txt' % (self.config['templates']['glue'], template)
			if os.path.exists(self._filepath):
				array = self.explodeUnescape('=', '\\', open(self._filepath).read())
				first = array[0]
				if first:
					self._filepath = '%s/%s.tpl' % (self.config['templates']['content'], array[0])
					if os.path.exists(self._filepath):
						content = open(self._filepath).read()
						self.log['getTemplate'][key]['content'] = [array[0], True, content]
					else:
						self.log['getTemplate'][key]['content'] = [array[0], False, content]
					del array[0]
				for value in array:
					if value:
						self._filepath = '%s/%s.py' % (self.config['templates']['code'], value)
						if os.path.exists(self._filepath):
							self.log['getTemplate'][key]['code'].append([value, True, False]);
							self.chain.append(template)
							content = self._includeFile(content)
							self.chain.pop()
							self.log['getTemplate'][key]['code'][-1][2] = content;
						else:
							self.log['getTemplate'][key]['code'].append([value, False, content]);
			else:
				raise SUITError('Template %s Not Found. See http://www.suitframework.com/docs/error2/' % template)
		else:
			raise SUITError('Infinite Loop Caused by %s. See http://www.suitframework.com/docs/error2/' % template)
		return content

	def parse(self, nodes, returnvalue, replace = [], escape = None, match = False, label = None):
		"""http://www.suitframework.com/docs/parse#howitworks"""
		returnvalue = str(returnvalue)
		escape = str((self.config['parse']['escape'], escape)[escape != None])
		nodes = copy.deepcopy(nodes)
		backtrace = inspect.stack()
		log = {
			'backtrace': backtrace[1],
			'content': returnvalue,
			'escape': escape,
			'label': label,
			'nodes': copy.deepcopy(nodes),
			'match': match,
			'replace': replace,
			'return': '',
			'tree': []
		}
		try:
			open = []
			close = []
			for key, value in enumerate(nodes):
				if len(value[0]) >= 1 and isinstance(value[0], list):
					for value2 in value:
						nodes.append(value2)
					del nodes[key]
			nodes.sort(key=lambda item: len(item[0]))
			for key, value in enumerate(nodes):
				valid = True
				nodes[key][0] = str(value[0])
				nodes[key][1] = str(value[1])
				if nodes[key][0] == nodes[key][1]:
					valid = False
				else:
					for value2 in open:
						if self._strpos(str(value2), nodes[key][0]) != -1 or not nodes[key][0]:
							valid = False
							break
				if valid:
					open.append(nodes[key][0])
					open.append(nodes[key][1])
				else:
					if value:
						raise SUITError('Duplicate opening returnvalue. See http://www.suitframework.com/docs/errors#error6')
					del nodes[key]
			nodessearch = copy.deepcopy(nodes)
			for key, value in enumerate(nodessearch):
				while (len(nodessearch[key]) < 6):
					nodessearch[key].append(False)
			stack = []
			tree = []
			skipnode = False
			pos = 0
			while pos != -1:
				opensmallest = -1
				closesmallest = -1
				opennode = []
				closenode = []
				if not skipnode:
					nonevalues = []
					for key, value in enumerate(nodessearch):
						if nodessearch[key][4] == False:
							nodepos = self._strpos(returnvalue, value[0], pos)
							if nodepos != -1:
								if nodepos < opensmallest or opensmallest == -1:
									opensmallest = nodepos
									opennode = value
							else:
								nodessearch[key][4] = True
						if nodessearch[key][5] == False:
							nodepos = self._strpos(returnvalue, value[1], pos)
							if nodepos != -1:
								if nodepos < closesmallest or closesmallest == -1:
									closesmallest = nodepos
									closenode = value
							else:
								nodessearch[key][5] = True
						if nodessearch[key][4] and nodessearch[key][5]:
							nonevalues.append(key)
					offset = 0
					for value in nonevalues:
						del nodessearch[value - offset]
						offset += 1
				else:
					closesmallest = self._strpos(returnvalue, skipnode[1], pos)
					closenode = skipnode
				if opensmallest < closesmallest and opensmallest != -1:
					pos = opensmallest
					result = self.parseUnescape(pos, escape, returnvalue)
					pos = result[1]
					returnvalue = result[2]
					if not result[0]:
						stack.append([opennode, pos])
						tree.append([])
					pos += len(opennode[0])
					if len(opennode) >= 4:
						skipnode = opennode
				elif closesmallest != -1:
					pos = closesmallest
					result = self.parseUnescape(pos, escape, returnvalue)
					pos = result[1]
					returnvalue = result[2]
					if not result[0]:
						if stack:
							openpop = stack.pop()
							if openpop[0][1] == closenode[1]:
								nodepop = tree.pop()
								start = openpop[1] + len(openpop[0][0])
								offset = 0
								for key, value in enumerate(nodepop):
									string = returnvalue[start:value[2][1]]
									nodepop.insert(key + offset, string)
									start = value[3] + len(value[2][0][1])
									offset += 1
								nodepop.append(returnvalue[start:pos])
								addition = [''.join((openpop[0][0], returnvalue[openpop[1] + len(openpop[0][0]):pos], openpop[0][1])), nodepop, openpop, pos]
								if stack:
									tree[-1].append(addition)
								else:
									tree.append(addition)
							else:
								tree.pop()
					pos += len(closenode[1])
					skipnode = False
				else:
					pos = -1
			for key, value in enumerate(tree):
				if not isinstance(value[0], str):
					for value2 in value:
						tree.append(value2)
					del tree[key]
			if not match:
				start = 0
				offset = 0
				iteratetree = copy.deepcopy(tree)
				for key, value in enumerate(iteratetree):
					string = self.replace(replace, returnvalue[start:value[2][1]], None, 3)
					tree.insert(key + offset, string)
					start = value[3] + len(value[2][0][1])
					offset += 1
				tree.append(self.replace(replace, returnvalue[start:len(returnvalue)], None, 3))
				returnvalue = self._walk(nodes, tree, replace, escape, 3, match)
				log['return'] = returnvalue
			else:
				returnvalue = (self._walk(nodes, tree, replace, escape, 3, match), returnvalue)
				log['return'] = pprint.pformat(returnvalue)
			log['tree'] = tree
		except TypeError:
			raise SUITError('Provided argument not array or improperly formatted one. See http://www.suitframework.com/docs/errors#error5')
			log['errors'] = True;
		if label != None:
			self.log['parse'].append(log)
		return returnvalue

	def parseConditional(self, ifstring, bool, elsestring = None, open = None, close = None, end = None):
		"""http://www.suitframework.com/docs/parseConditional#howitworks"""
		node = []
		ifstring = str(ifstring)
		open = str((self.config['parse']['open'], open)[open != None])
		close = str((self.config['parse']['close'], close)[close != None])
		end = str((self.config['parse']['end'], end)[end != None])
		node.append([
			''.join((open, ifstring, close)),
			''.join((open, end, ifstring, close)),
			('\'\'', 'case')[bool]
		])
		if elsestring != None:
			elsestring = str(elsestring)
			node.append([
				''.join((open, elsestring, close)),
				''.join((open, end, elsestring, close)),
				('case', '\'\'')[bool]
			])
		return node

	def parseLoop(self, string, array, implode = '', open = None, close = None, end = None):
		"""http://www.suitframework.com/docs/parseLoop#howitworks"""
		node = []
		string = str(string)
		open = str((self.config['parse']['open'], open)[open != None])
		close = str((self.config['parse']['close'], close)[close != None])
		end = str((self.config['parse']['end'], end)[end != None])
		try:
			node = [
				''.join((open, string, close)),
				''.join((open, end, string, close)),
				''.join(('suit.parseLoopNode(case, nodes, replace, escape, key, ', repr(array), ', ', repr(implode), ')')),
				True
			]
		except TypeError:
			raise SUITError('Provided argument not array or improperly formatted one. See http://www.suitframework.com/docs/errors#error5')
		return node

	def parseLoopNode(self, case, nodes, replace, escape, key, array, implode):
		replacements = []
		try:
			if len(replace) >= 1 and not isinstance(replace[0], list):
				replace = [replace]
			if len(replace) >= 1 and isinstance(replace[0], list) and len(replace[0]) >= 1 and not isinstance(replace[0][0], list):			
				replace = [replace]
			for value in array:
				if len(value[0]) >= 1 and not isinstance(value[0][0], list):
					value[0] = [value[0]]
				if len(value[0]) >= 1 and isinstance(value[0][0], list) and len(value[0][0]) >= 1 and not isinstance(value[0][0][0], list):			
					value[0] = [value[0]]
				value[1].extend(nodes)
				value[0].extend(replace)
				if len(value) >= 3:
					label = value[2]
				else:
					label = None
				replacements.append(self.parse(value[1], case, value[0], escape, False, label))
		except TypeError:
			raise SUITError('Provided argument not array or improperly formatted one. See http://www.suitframework.com/docs/errors#error5')
		return implode.join(replacements)

	def parseUnescape(self, pos, escape, content):
		"""http://www.suitframework.com/docs/parseUnescape#howitworks"""
		pos = int(pos)
		content = str(content)
		escape = str(escape)
		count = 0
		if escape:
			start = pos - len(escape)
			while abs(start) == start and content[start:start + len(escape)] == escape:
				count += len(escape)
				start = pos - count - len(escape)
			count = count / len(escape)
		condition = count % 2
		if condition:
			count += 1
		pos -= len(escape) * (count / 2)
		content = content[0:pos] + content[pos + len(escape) * (count / 2):len(content)]
		return (condition, pos, content)

	def parseVars(self, case):
		array = self.explodeUnescape('=>', self.config['parse']['escape'], case)
		arrays = '';
		for value in array:
			arrays = ''.join((arrays, '[', repr(value), ']'))
		return eval('self.vars' + arrays);

	def replace(self, array, string, label = None, backtracekey = 2):
		string = str(string)
		array = copy.deepcopy(array)
		backtrace = inspect.stack()
		log = {
			'array': copy.deepcopy(array),
			'backtrace': backtrace[backtracekey],
			'return': string,
			'label': label,
			'replace': []
		}
		try:
			if len(array) >= 1 and not isinstance(array[0], list):
				array = [array]
			if len(array) >= 1 and isinstance(array[0], list) and len(array[0]) >= 1 and not isinstance(array[0][0], list):			
				array = [array]
			for key, value in enumerate(array):
				array[key].sort(key=lambda item: len(item[0]))
				pos = 0
				copyarray = copy.deepcopy(value)
				while pos != -1:
					smallest = -1
					replace = []
					for key2, value2 in enumerate(array[key]):
						if isinstance(value2, list) and len(value2) >= 2:
							stringpos = self._strpos(string, str(value2[0]), pos)
							if stringpos != -1:
								if stringpos < smallest or smallest == -1:
									smallest = stringpos
									replace = value2
							else:
								array[key][key2] = None
						else:
							raise TypeError
					for key2, value2 in enumerate(array[key]):
						if value2 == None:
							del array[key][key2]
					pos = smallest
					if pos != -1:
						string = ''.join((string[0:pos], str(replace[1]), string[pos + len(str(replace[0])):len(string)]))
						pos += len(str(replace[1]))
				log['replace'].append([copyarray, string]);
		except TypeError:
			raise SUITError('Provided argument not array or improperly formatted one. See http://www.suitframework.com/docs/errors#error5')
		if label != None:
			self.log['replace'].append(log)
		return string